<?php

namespace App\Imports;

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

            // if (isset($row['student_id'])) {
            //     $student = Student::where('student_number', $row['student_id'])
            //         ->orWhere('id', $row['student_id'])
            //         ->first();
                
            //     if (!$student) {
            //         $errors[] = 'Student not found';
            //     }
            // } else {
            //     $errors[] = 'Student ID is required';
            // }

            // if (isset($row['subject_code'])) {
            //     $subject = Subject::where('code', $row['subject_code'])->first();
                
            //     if (!$subject) {
            //         $errors[] = 'Subject not found';
            //     }
            // } else {
            //     $errors[] = 'Subject code is required';
            // }

            // if (!isset($row['unit_type'])) {
            //     $errors[] = 'Unit Type is required';
            // }
            
            // if (!isset($row['grade']) || !is_numeric($row['grade'])) {
            //     $errors[] = 'Invalid grade';
            // }
            // if (!isset($row['school_year'])) {
            //     $errors[] = 'School year is required';
            // }

            // if (!isset($row['semester'])) {
            //     $errors[] = 'Semester is required';
            // }

            // Create import row
            GradeImportRow::create([
                'grade_import_id' => $this->gradeImportId,
                'raw_student_identifier' => $row['student_id'] ?? '',
                // 'student_id' => $student?->id,
                'student_id' => $row['student_id'] ?? '',
                'subject_code' => $row['subject_code'] ?? null,
                'subject_name' => $row['subject_name'] ?? null,
                'unit_type' => $row['unit_type'] ?? null,
                'school_year' => $row['school_year'] ?? null,
                'semester' => $row['semester'] ?? null,
                'faculty' => $row['faculty'] ?? null,
                'credit_unit' => $row['credit_unit'] ?? null,
                'grade' => $row['grade'] ?? null,
                'status' => empty($errors) ? 'valid' : 'invalid',
                'errors' => !empty($errors) ? json_encode($errors) : null,
            ]);
        }
    }
}