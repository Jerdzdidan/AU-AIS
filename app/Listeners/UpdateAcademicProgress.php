<?php

namespace App\Listeners;

use App\Enums\GradeStatus;
use App\Models\StudentSubjectProgress;
use App\Models\Grade;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use PDO;

class UpdateAcademicProgress
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */

    public function handle(object $event): void
    {
        $student = $event->student;

        StudentSubjectProgress::where('student_id', $student->id)->update([
            'lecture_status' => false,
            'laboratory_status' => false,
        ]);

        $subjects = $student->studentSubjectProgress;

        // foreach ($grades as $grade) {
        //     $subjectProgress = $subjects->first(function ($progress) use ($grade) {
        //         return $progress->subject->code === $grade->subject_code;
        //     });
        //
        //     if (!$subjectProgress) {
        //         continue;
        //     }
        //
        //
        //     // First calculate lecture status
        //     if ($grade->has_lec())
        //     {
        //
        //     }
        //
        //     if ($grade->unit_type == 'lec' && $grade->is_passed()) {
        //         $subjectProgress->lecture_status = "completed";
        //     }
        //     if ($grade->unit_type == 'lab' && $grade->is_passed()) {
        //         $subjectProgress->laboratory_status = "completed";
        //     }
        //
        //     $subjectProgress->save();
        // }

        foreach ($subjects as $subject) {
            if ($subject->has_lec()) {
                $grade = get_grade($student->id, $subject->subject->code, 'lec');

                if ($grade == -1) {
                    $subject->lecture_status = GradeStatus::DROPPED;
                } else if ($grade = 0) {
                    $subject->lecture_status = GradeStatus::INCOMPLETE;
                } else if ($grade >= 1 && $grade <= 3) {
                    $subject->lecture_status = GradeStatus::COMPLETED;
                } else {
                    $subject->lecture_status = GradeStatus::FAILED;
                }
            }

            if ($subject->has_lab()) {
                $grade = get_grade($student->id, $subject->subject->code, 'lab');

                if ($grade == -1) {
                    $subject->lecture_status = GradeStatus::DROPPED;
                } else if ($grade = 0) {
                    $subject->lecture_status = GradeStatus::INCOMPLETE;
                } else if ($grade >= 1 && $grade <= 3) {
                    $subject->lecture_status = GradeStatus::COMPLETED;
                } else {
                    $subject->lecture_status = GradeStatus::FAILED;
                }
            }

            if ($subject->has_lec() && $subject->has_lab()) {
                if ($subject->lecture_status == GradeStatus::DROPPED || $subject->laboratory_status == GradeStatus::DROPPED) {
                    $subject->remarks = GradeStatus::DROPPED;
                } else if ($subject->lecture_status == GradeStatus::INCOMPLETE || $subject->laboratory_status == GradeStatus::INCOMPLETE) {
                    $subject->remarks = GradeStatus::INCOMPLETE;
                } else if ($subject->lecture_status == GradeStatus::FAILED || $subject->laboratory_status == GradeStatus::FAILED) {
                    $subject->remarks = GradeStatus::FAILED;
                } else {
                    $subject->remarks = GradeStatus::COMPLETED;
                }
            } else if ($subject->has_lec()) {
                if ($subject->lecture_status == GradeStatus::DROPPED) {
                    $subject->remarks = GradeStatus::DROPPED;
                } else if ($subject->lecture_status == GradeStatus::INCOMPLETE) {
                    $subject->remarks = GradeStatus::INCOMPLETE;
                } else if ($subject->lecture_status == GradeStatus::FAILED) {
                    $subject->remarks = GradeStatus::FAILED;
                } else {
                    $subject->remarks = GradeStatus::COMPLETED;
                }
            } else {
                if ($subject->laboratory_status == GradeStatus::DROPPED) {
                    $subject->remarks = GradeStatus::DROPPED;
                } else if ($subject->laboratory_status == GradeStatus::INCOMPLETE) {
                    $subject->remarks = GradeStatus::INCOMPLETE;
                } else if ($subject->laboratory_status == GradeStatus::FAILED) {
                    $subject->remarks = GradeStatus::FAILED;
                } else {
                    $subject->remarks = GradeStatus::COMPLETED;
                }
            }

            if ($subject->has_lec() && $subject->has_lab()) {
                $lec_grade = get_grade($student->id, $subject->subject->code, 'lec');
                $lab_grade = get_grade($student->id, $subject->subject->code, 'lab');
                $lec_unit = get_unit($student->id, $subject->subject->code, 'lec');
                $lab_unit = get_unit($student->id, $subject->subject->code, 'lab');

                if ($lec_grade == -1 || $lab_grade == -1) {
                    $subject->final_grade = "DRP";
                } else if ($lec_grade == 0 || $lab_grade == 0) {
                    $subject->final_grade = "INC";
                } else if ($lec_grade == 5 || $lab_grade == 5) {
                    $subject->final_grade = 5;
                } else {
                    $f_grade = ($lec_grade + $lab_grade) / ($lec_unit + $lab_unit);
                    $subject->final_grade = $f_grade;
                }
            } else if ($subject->has_lec()) {
                $grade = get_grade($student->id, $subject->subject->code, 'lec');

                if ($grade == -1) {
                    $subject->final_grade = "DRP";
                } else if ($grade == 0) {
                    $subject->final_grade = "INC";
                } else if ($grade == 5) {
                    $subject->final_grade = 5;
                } else {
                    $subject->final_grade = $grade;
                }
            } else {
                $grade = get_grade($student->id, $subject->subject->code, 'lab');

                if ($grade == -1) {
                    $subject->final_grade = "DRP";
                } else if ($grade == 0) {
                    $subject->final_grade = "INC";
                } else if ($grade == 5) {
                    $subject->final_grade = 5;
                } else {
                    $subject->final_grade = $grade;
                }
            }
        }

        function get_grade($student_id, $subject_code, $unit_type)
        {
            $grade = Grade::where('student_id', $student_id)
                ->where('subject_code', $subject_code)
                ->where('unit_type', $unit_type)
                ->orderByDesc('school_year')
                ->orderByDesc('semester')
                ->select('grade')
                ->first();

            return $grade;
        }

        function get_unit($student_id, $subject_code, $unit_type)
        {
            $credit_unit = Grade::where('student_id', $student_id)
                ->where('subject_code', $subject_code)
                ->where('unit_type', $unit_type)
                ->orderByDesc('school_year')
                ->orderByDesc('semester')
                ->select('credit_unit')
                ->first();

            return $credit_unit;
        }
    }
}
