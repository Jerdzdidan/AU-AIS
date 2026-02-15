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
            'lecture_status' => null,
            'laboratory_status' => null,
        ]);

        $subjects = $student->studentSubjectProgress;

        // Preload all grades at once to avoid N+1 queries
        $allGrades = Grade::where('student_id', $student->id)
            ->orderByDesc('school_year')
            ->orderByDesc('semester')
            ->get()
            ->groupBy('subject_code');

        foreach ($subjects as $subject) {
            $subjectCode = $subject->subject->code;

            // Get grades from preloaded data
            $lecGrade = $this->getGradeFromCollection($allGrades, $subjectCode, 'lec');
            $labGrade = $this->getGradeFromCollection($allGrades, $subjectCode, 'lab');
            $lecUnit = $this->getUnitFromCollection($allGrades, $subjectCode, 'lec');
            $labUnit = $this->getUnitFromCollection($allGrades, $subjectCode, 'lab');

            // Update lecture status
            if ($subject->has_lec()) {
                $subject->lecture_status = $this->determineStatus($lecGrade);
            }

            // Update laboratory status
            if ($subject->has_lab()) {
                $subject->laboratory_status = $this->determineStatus($labGrade);
            }

            // Update remarks
            if ($subject->has_lec() && $subject->has_lab()) {
                $subject->remarks = $this->determineCombinedRemarks(
                    $lecGrade,
                    $labGrade,
                    $subject->lecture_status,
                    $subject->laboratory_status
                );
            } else if ($subject->has_lec()) {
                $subject->remarks = $lecGrade === null ? null : $subject->lecture_status;
            } else {
                $subject->remarks = $labGrade === null ? null : $subject->laboratory_status;
            }

            // Calculate final grade
            $subject->final_grade = $this->calculateFinalGrade(
                $lecGrade,
                $labGrade,
                $lecUnit,
                $labUnit,
                $subject->has_lec(),
                $subject->has_lab()
            );

            $subject->save(); // CRITICAL: Save the changes
        }
    }

    private function getGradeFromCollection($gradesCollection, $subjectCode, $unitType)
    {
        if (!isset($gradesCollection[$subjectCode])) {
            return null;
        }

        $grade = $gradesCollection[$subjectCode]
            ->where('unit_type', $unitType)
            ->first();

        return $grade ? $grade->grade : null;
    }

    private function getUnitFromCollection($gradesCollection, $subjectCode, $unitType)
    {
        if (!isset($gradesCollection[$subjectCode])) {
            return null;
        }

        $grade = $gradesCollection[$subjectCode]
            ->where('unit_type', $unitType)
            ->first();

        return $grade ? $grade->credit_unit : null;
    }

    private function determineStatus($grade)
    {
        if ($grade === null) {
            return null;
        } else if ($grade == -1) {
            return GradeStatus::DROPPED;
        } else if ($grade == 0) {
            return GradeStatus::INCOMPLETE;
        } else if ($grade >= 1 && $grade <= 3) {
            return GradeStatus::COMPLETED;
        } else if ($grade == 5) {
            return GradeStatus::FAILED;
        }
    }

    private function determineCombinedRemarks($lecGrade, $labGrade, $lecStatus, $labStatus)
    {
        if ($lecGrade === null && $labGrade === null) {
            return null;
        } else if ($lecStatus == GradeStatus::DROPPED || $labStatus == GradeStatus::DROPPED) {
            return GradeStatus::DROPPED;
        } else if ($lecStatus == GradeStatus::INCOMPLETE || $labStatus == GradeStatus::INCOMPLETE) {
            return GradeStatus::INCOMPLETE;
        } else if ($lecStatus == GradeStatus::FAILED || $labStatus == GradeStatus::FAILED) {
            return GradeStatus::FAILED;
        } else {
            return GradeStatus::COMPLETED;
        }
    }

    private function calculateFinalGrade($lecGrade, $labGrade, $lecUnit, $labUnit, $hasLec, $hasLab)
    {
        if ($hasLec && $hasLab) {
            if ($lecGrade === null && $labGrade === null) {
                return null;
            } else if ($lecGrade == -1 || $labGrade == -1) {
                return "DRP";
            } else if ($lecGrade == 0 || $labGrade == 0) {
                return "INC";
            } else {
                if ($lecGrade === null) {
                    return round($labGrade / $labUnit);
                } else if ($labGrade === null) {
                    return round($lecGrade / $lecUnit); // FIXED: was $lec_grade / $lec_grade
                } else {
                    return round((($lecGrade * $lecUnit) + ($labGrade * $labUnit)) / ($lecUnit + $labUnit), 2);
                }
            }
        } else if ($hasLec) {
            return $this->calculateSingleGrade($lecGrade);
        } else {
            return $this->calculateSingleGrade($labGrade);
        }
    }

    private function calculateSingleGrade($grade)
    {
        if ($grade === null) {
            return null;
        } else if ($grade == -1) {
            return "DRP";
        } else if ($grade == 0) {
            return "INC";
        } else {
            return $grade;
        }
    }
}
