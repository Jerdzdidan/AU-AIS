<?php

namespace App\Http\Controllers\StudentPortal;

use App\Events\StudentAcademicProgressCreate;
use App\Http\Controllers\Controller;
use App\Models\StudentSubjectProgress;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\DataTables;

class AcademicProgress extends Controller
{
    //
    public function index()
    {
        $student = Auth::user()->student;

        event(new StudentAcademicProgressCreate($student));

        return view('app.student_portal.academic_progress.index');
    }

    public function getData(Request $request)
    {
        $academicProgress = StudentSubjectProgress::where(
                'student_id',
                Auth::user()->student->id
            )
            ->with('subject:id,code,name,lec_units,lab_units,prerequisites,subject_category,year_level,semester')
            ->select([
                'student_subject_progress.id',
                'student_subject_progress.subject_id',
                'student_subject_progress.lecture_completed',
                'student_subject_progress.laboratory_completed',
            ]);

        if ($request->filled('status') && $request->status !== 'All') {
            if ($request->status === 'Complete') {
                $academicProgress->where('lecture_completed', true)->where('laboratory_completed', true);
            } elseif ($request->status === 'Incomplete') {
                $academicProgress->where('lecture_completed', false)->where('laboratory_completed', false);
            }
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
            ->addColumn('is_completed', fn($row) => $row->isCompleted())
            ->addColumn('total_units', fn($row) =>
                ($row->subject?->lec_units ?? 0) + ($row->subject?->lab_units ?? 0)
            )
            ->make(true);
    }


    public function getStats()
    {
        $student = Auth::user()->student;

        $academicProgress = StudentSubjectProgress::where('student_id', $student->id)
            ->with('subject:id,lec_units,lab_units') 
            ->get();

        $units_completed = $academicProgress->sum(function ($progress) {
            return ($progress->isCompleted()) ? ($progress->lec_units + $progress->lab_units) : 0;
        });
        $total_units = $academicProgress->sum(function ($progress) {
            return $progress->lec_units + $progress->lab_units;
        });
        $units_progress = $total_units > 0 ? $units_completed / $total_units * 100 : 0;


        $subjects_completed = $academicProgress->where('lecture_status', 'completed')
                  ->where('lab_status', 'completed')
                  ->count();
        $total_subjects = $academicProgress->count();

        return response()->json([
            'units_earned' => $units_completed,
            'total_units' => $total_units,
            'units_progress' => round($units_progress, 2),
            'total_subjects' => $total_subjects,
            'subjects_completed' => $subjects_completed,
        ]);
    }
}
