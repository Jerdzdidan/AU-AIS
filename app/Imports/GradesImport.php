<?php

namespace App\Imports;

use App\Models\GradeImport;
use App\Models\GradeImportRow;
use App\Models\Student;
use App\Models\Subject;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;

class GradesImport implements ToCollection, WithHeadingRow
{
    private $gradeImportId;

    public function __construct(int $gradeImportId)
    {
        $this->gradeImportId = $gradeImportId;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $errors = [];
            $student = null;
            $subject = null;

            if (isset($row['student_number'])) {
                $student = Student::where('student_number', $row['student_number'])
                    ->first();
                if (!$student) {
                    $errors[] = 'Student not found';
                }
            } else {
                $errors[] = 'Student ID is required';
            }

            if (isset($row['subject_code'])) {
                $subject = Subject::where('code', $row['subject_code'])->first();
                
                if (!$subject) {
                    $errors[] = 'Subject not found';
                }
                else {
                    $row['subject_name'] = $subject->name;
                }
            } else {
                $errors[] = 'Subject code is required';
            }

            if (!isset($row['unit_type'])) {
                $errors[] = 'Unit Type is required';
            }
            else if ($row['unit_type'] !== 'lec' && $row['unit_type'] !== 'lab') {
                $errors[] = 'Invalid Unit Type (should be "lec" or "lab")';
            }

            if (!isset($row['faculty'])) {
                $errors[] = 'Faculty is required';
            }

            if (!isset($row['credit_unit']) || !is_numeric($row['credit_unit'])) {
                $errors[] = 'Invalid credit unit';
            }
            
            if (!isset($row['grade']) || !is_numeric($row['grade'])) {
                $errors[] = 'Invalid grade';
            }

            $gradeImport = GradeImport::where('id', $this->gradeImportId)->first();

            $school_year = $gradeImport?->academic_period?->school_year ?? null;
            $semester = $gradeImport?->academic_period?->semester ?? null;

            // Create import row
            GradeImportRow::create([
                'grade_import_id' => $this->gradeImportId,
                'student_number' => $row['student_number'] ?? '',
                'subject_code' => $row['subject_code'] ?? null,
                'subject_name' => $row['subject_name'] ?? null,
                'unit_type' => $row['unit_type'] ?? null,
                'school_year' => $school_year,
                'semester' => $semester,
                'faculty' => $row['faculty'] ?? null,
                'credit_unit' => $row['credit_unit'] ?? null,
                'grade' => $row['grade'] ?? null,
                'validity' =>  empty($errors) ? 'valid' : 'invalid',
                'status' => 'staged',
                'errors' => !empty($errors) ? json_encode($errors) : null,
            ]);
        }
    }
}