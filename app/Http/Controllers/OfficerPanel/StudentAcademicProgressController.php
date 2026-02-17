<?php

namespace App\Http\Controllers\OfficerPanel;

use App\Events\StudentAcademicProgressCreate;
use App\Events\StudentCheckProgress;
use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentSubjectProgress;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\Facades\DataTables;

class StudentAcademicProgressController extends Controller
{
    //
    public function index(Request $request)
    {
        return view('app.officer_panel.student_academic_progress.index');
    }

    public function getData(Request $request)
    {
        $department = auth()->user()->department_id;

        $students = Student::with(['user:id,name,email,status', 'program:id,name,code,department_id', 'curriculum:id,year_start,year_end'])
            // ->where('status', 'Active')
            ->whereHas('user', function ($query) {
                $query->where('status', true);
            })
            ->whereHas('program', function ($query) use ($department) {
                $query->where('department_id', $department);
            })
            ->select(['id', 'user_id', 'student_number', 'program_id', 'curriculum_id', 'year_level']);

        return DataTables::of($students)
            ->addColumn('curriculum', function ($row) {
                return $row->program->code . ' - Curriculum (' . $row->curriculum->year_start . '-' . $row->curriculum->year_end . ')';
            })
            ->editColumn('id', function ($row) {
                return Crypt::encryptString($row->id);
            })
            ->editColumn('user_id', function ($row) {
                return Crypt::encryptString($row->user_id);
            })
            ->make(true);
    }

    public function getStats(Request $request)
    {
        $department = auth()->user()->department_id;

        $students_count = Student::with(['user:status', 'program:department_id'])
            ->whereHas('user', function ($query) {
                $query->where('status', true);
            })
            ->whereHas('program', function ($query) use ($department) {
                $query->where('department_id', $department);
            })
            ->count();

        return response()->json([
            'total' => $students_count,
        ]);
    }

    public function show($student_id)
    {
        $decrypted = Crypt::decryptString($student_id);
        $student = Student::with('user')->findOrFail($decrypted);

        event(new StudentAcademicProgressCreate($student));
        event(new StudentCheckProgress($student));


        return view('app.officer_panel.student_academic_progress.show', [
            'student' => $student,
            'student_id' => $student_id
        ]);
    }

    public function getProgressData(Request $request, $student_id)
    {
        $decrypted = Crypt::decryptString($student_id);

        $query = StudentSubjectProgress::where('student_id', $decrypted)
            ->with('subject:id,code,name,lec_units,lab_units,prerequisites,subject_category,year_level,semester')
            ->select([
                'student_subject_progress.id',
                'student_subject_progress.subject_id',
                'student_subject_progress.lecture_status',
                'student_subject_progress.laboratory_status',
                'student_subject_progress.final_grade',
                'student_subject_progress.remarks',
            ])
            ->join('subjects', 'student_subject_progress.subject_id', '=', 'subjects.id')
            ->orderBy('subjects.year_level', 'asc')
            ->orderBy('subjects.semester', 'asc')
            ->orderBy('subjects.code', 'asc');

        if ($request->filled('year_level') && $request->year_level !== 'all') {
            if ($request->year_level === 'minor') {
                $query->whereHas('subject', function ($q) {
                    $q->where('subject_category', 'Minor');
                });
            } else {
                $query->whereHas('subject', function ($q) use ($request) {
                    $q->where('year_level', $request->year_level);
                });
            }
        }

        $academicProgress = $query->get();

        if ($request->filled('status') && $request->status !== 'All') {
            $academicProgress = $academicProgress->filter(function ($progress) use ($request) {
                return $request->status === 'Complete'
                    ? $progress->isCompleted()
                    : !$progress->isCompleted();
            });
        }

        return DataTables::of($academicProgress)
            ->editColumn('id', function ($row) {
                return Crypt::encryptString($row->id);
            })
            ->editColumn('subject_id', function ($row) {
                return Crypt::encryptString($row->subject_id);
            })
            ->editColumn('subject.id', function ($row) {
                return Crypt::encryptString($row->subject->id);
            })
            ->addColumn('has_lec', fn($row) => $row->subject->lec_units > 0)
            ->addColumn('has_lab', fn($row) => $row->subject->lab_units > 0)
            ->addColumn('is_completed', fn($row) => $row->isCompleted())
            ->addColumn(
                'total_units',
                fn($row) => ($row->subject?->lec_units ?? 0) + ($row->subject?->lab_units ?? 0)
            )
            ->make(true);
    }

    public function getProgressStats($student_id)
    {
        $decrypted = Crypt::decryptString($student_id);
        $student = Student::findOrFail($decrypted);

        $academicProgress = StudentSubjectProgress::where('student_id', $student->id)
            ->with('subject:id,lec_units,lab_units')
            ->get();

        $units_completed = $academicProgress->sum(function ($progress) {
            if ($progress->isCompleted()) {
                return ($progress->subject?->lec_units ?? 0) + ($progress->subject?->lab_units ?? 0);
            }

            return 0;
        });

        $total_units = $academicProgress->sum(function ($progress) {
            return ($progress->subject?->lec_units ?? 0) + ($progress->subject?->lab_units ?? 0);
        });

        $units_progress = $total_units > 0 ? $units_completed / $total_units * 100 : 0;


        $subjects_completed = $academicProgress->filter(function ($progress) {
            return $progress->isCompleted();
        })->count();

        $total_subjects = $academicProgress->count();

        return response()->json([
            'units_earned' => $units_completed,
            'total_units' => $total_units,
            'units_progress' => round($units_progress, 2),
            'total_subjects' => $total_subjects,
            'subjects_completed' => $subjects_completed,
        ]);
    }

    public function progressDownloadPdf($student_id)
    {
        $decrypted = Crypt::decryptString($student_id);
        $student = Student::with('user')->findOrFail($decrypted);

        $user = $student->user;

        $allProgress = StudentSubjectProgress::where('student_id', $student->id)
            ->with('subject:id,code,name,lec_units,lab_units,prerequisites,subject_category,year_level,semester')
            ->select([
                'student_subject_progress.id',
                'student_subject_progress.subject_id',
                'student_subject_progress.lecture_status',
                'student_subject_progress.laboratory_status',
                'student_subject_progress.final_grade',
                'student_subject_progress.remarks',
            ])
            ->join('subjects', 'student_subject_progress.subject_id', '=', 'subjects.id')
            ->orderBy('subjects.year_level', 'asc')
            ->orderBy('subjects.semester', 'asc')
            ->orderBy('subjects.code', 'asc')
            ->get();

        // Group by year level and category (minor)
        $years = [
            '1' => [],
            '2' => [],
            '3' => [],
            '4' => [],
            'minor' => []
        ];

        // Populate the years array
        foreach ($allProgress as $progress) {
            if ($progress->subject->subject_category === 'MINOR') {
                $years['minor'][] = $progress;
            } else {
                $yearLevel = (string) $progress->subject->year_level;
                if (isset($years[$yearLevel])) {
                    $years[$yearLevel][] = $progress;
                }
            }
        }

        // Calculate stats
        $units_completed = $allProgress->sum(function ($progress) {
            if ($progress->isCompleted()) {
                return ($progress->subject?->lec_units ?? 0) + ($progress->subject?->lab_units ?? 0);
            }
            return 0;
        });

        $total_units = $allProgress->sum(function ($progress) {
            return ($progress->subject?->lec_units ?? 0) + ($progress->subject?->lab_units ?? 0);
        });

        $units_progress = $total_units > 0 ? $units_completed / $total_units * 100 : 0;
        $subjects_completed = $allProgress->filter(function ($progress) {
            return $progress->isCompleted();
        })->count();

        return view('app.officer_panel.student_academic_progress.pdf', [
            'user' => $user,
            'student' => $student,
            'years' => $years,
            'stats' => [
                'units_earned' => $units_completed,
                'total_units' => $total_units,
                'units_progress' => round($units_progress, 2),
                'total_subjects' => $allProgress->count(),
                'subjects_completed' => $subjects_completed,
            ]
        ]);
    }
}
