<?php

namespace App\Listeners;

use App\Models\StudentSubjectProgress;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

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
            'lecture_status' => "NOT_TAKEN",
            'laboratory_status' => "NOT_TAKEN",
        ]);

        $subjects = $student->studentSubjectProgress;

        foreach ($subjects as $subject) {
            $lec = $student->grades->where('subject_code', $subject->subject->code)
                ->where('unit_type', 'lec')
                ->first();
            $lab = $student->grades->where('subject_code', $subject->subject->code)
                ->where('unit_type', 'lab')
                ->first();

            $lec_raw = $lec ? $lec->grade : null;
            $lab_raw = $lab ? $lab->grade : null;

            $subject->lecture_grade = $lec_raw;
            $subject->laboratory_grade = $lab_raw;

            $normalize = function ($g) {
                if ($g === null || $g === '') return null;
                if (is_numeric($g)) return floatval($g);
                $u = strtoupper(trim($g));
                if ($u === 'INC' || $u === 'DRP') return $u;
                return null;
            };

            $isPassingNumeric = function ($n) {
                return is_numeric($n) && $n >= 1.00 && $n <= 3.00;
            };

            $lec = $normalize($lec_raw);
            $lab = $normalize($lab_raw);

            $componentStatus = function ($grade) use ($isPassingNumeric) {
                if ($grade === null) return 'NOT_TAKEN';
                if ($grade === 'DRP') return 'NOT_TAKEN'; 
                if ($grade === 'INC') return 'FAILED';
                if (is_numeric($grade)) {
                    return $isPassingNumeric($grade) ? 'COMPLETED' : 'FAILED';
                }
                return 'NOT_TAKEN';
            };

            $subject->lecture_status = $subject->hasLec()
                ? $componentStatus($lec)
                : 'NOT_TAKEN';

            $subject->laboratory_status = $subject->hasLab()
                ? $componentStatus($lab)
                : 'NOT_TAKEN';

            $final_grade = null;
            $remarks = null;

            if ($lec === 'DRP' || $lab === 'DRP') {
                $final_grade = 'DRP';
                $remarks = 'DROPPED';
            } elseif ($lec === 'INC' || $lab === 'INC') {
                $final_grade = 'INC';
                $remarks = 'INCOMPLETE';
            } else {
                // compute weighted numeric average if numeric grades exist
                $total_units = 0;
                $total_points = 0.0;

                if (is_numeric($lec) && $subject->subject->lec_units > 0) {
                    $total_units += $subject->subject->lec_units;
                    $total_points += $lec * $subject->subject->lec_units;
                }
                if (is_numeric($lab) && $subject->subject->lab_units > 0) {
                    $total_units += $subject->subject->lab_units;
                    $total_points += $lab * $subject->subject->lab_units;
                }

                if ($total_units > 0) {
                    $avg = round($total_points / $total_units, 2);
                    $final_grade = $avg;
                    $remarks = ($avg <= 3.00) ? 'COMPLETED' : 'FAILED';
                } else {
                    $final_grade = null;
                    $remarks = 'NOT_TAKEN';
                }
            }

            $subject->grade = is_numeric($final_grade) ? $final_grade : $subject->grade; 
            $subject->remarks = $remarks;

            $subject->save();
        }
    }
}
