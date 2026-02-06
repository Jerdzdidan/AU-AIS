<?php

namespace App\Http\Controllers\StudentPortal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GradeController extends Controller
{
    //
    public function index() {
        $student = Auth::user()->student;

        $grades = $student->grades()->get();

        $groupedGrades = $grades->groupBy(function($grade) {
            return $grade->school_year . ' - ' . $grade->semester;
        });

        $gradeCards = $groupedGrades->map(function($periodGrades, $academicPeriod) {
            $sortedGrades = $periodGrades->sortBy('subject_code');

            $totalWeightedGrade = 0;
            $totalUnits = 0;

            foreach ($sortedGrades as $grade) {
                if ($grade->grade >= 1 && $grade->grade <= 3) {
                    $totalWeightedGrade += ($grade->grade * $grade->credit_unit);
                    $totalUnits += $grade->credit_unit;
                }
            }

            $gwa = $totalUnits > 0 ? round($totalWeightedGrade / $totalUnits, 2) : 0;

            $subjects = $sortedGrades->map(function($grade) {
                return [
                    'subjectCode' => $grade->subject_code,
                    'subjectName' => $grade->subject_name,
                    'unitType' => $grade->unit_type,
                    'creditUnit' => $grade->credit_unit,
                    'faculty' => $grade->faculty,
                    'grade' => $grade->grade,
                ];
            })->values()->toArray();

            return [
                'academicPeriod' => $grade->gradeImportRow->gradeImport->academic_period->name,
                'gwa' => $gwa,
                'totalUnits' => $totalUnits,
                'subjects' => $subjects,
                '_school_year' => $sortedGrades->first()->school_year,
                '_semester' => $sortedGrades->first()->semester,
            ];
        });

        $gradeCards = $gradeCards->sortByDesc(function($card) {
            $years = explode('-', $card['_school_year']);
            $endYear = end($years);
            
            $semesterValue = stripos($card['_semester'], '2nd') !== false ? 2 : 1;
            
            return ($endYear * 10) + $semesterValue;
        })->values();

        $gradeCards = $gradeCards->map(function($card) {
            unset($card['_school_year'], $card['_semester']);
            return $card;
        })->toArray();

        return view('app.student_portal.grades.index', [
            'student' => $student,
            'grades' => $grades,
            'gradeCards' => $gradeCards,
        ]);
    }
}
