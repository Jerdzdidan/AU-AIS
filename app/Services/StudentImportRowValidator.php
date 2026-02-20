<?php

namespace App\Services;

use App\Models\Grade;
use App\Models\GradeImport;
use App\Models\GradeImportRow;
use App\Models\Student;
use App\Models\StudentImport;
use App\Models\StudentImportRow;
use Illuminate\Support\Collection;

class GradeImportRowValidator
{
    /**
     * Validate a grade import row
     *
     * @param StudentImportRow $row
     * @param Collection $students Keyed by student_number
     * @param Collection $programs Keyed by code
     * @param StudentImport $student_import
     * @return array Array of error messages
     */
    public function validateRow(
        StudentImportRow $row,
        Collection $students,
        Collection $programs,
        StudentImport $student_import
    ): array {
        $errors = [];

        // Validate student
        $errors = array_merge($errors, $this->validateStudent($row, $students));

        // Validate subject
        $errors = array_merge($errors, $this->validateSubject($row, $subjects));

        // Validate unit type
        $errors = array_merge($errors, $this->validateUnitType($row));

        // Validate duplicate entry
        $errors = array_merge($errors, $this->validateDuplicateEntry($row, $gradeImport));

        // Validate faculty
        $errors = array_merge($errors, $this->validateFaculty($row));

        // Validate credit unit
        $errors = array_merge($errors, $this->validateCreditUnit($row));

        // Validate grade
        $errors = array_merge($errors, $this->validateGrade($row));

        return $errors;
    }

    /**
     * Validate student exists
     */
    protected function validateStudent(StudentImport $row, Collection $students): array
    {
        $errors = [];

        if (empty($row->student_number)) {
            $errors[] = 'Student number is required';
            return $errors;
        }

        $student = $students->get($row->student_number);

        if ($student) {
            $errors[] = 'Student already exists';
        } else {
            if (!preg_match('/^\d{2}-\d{5}$/', $row['student_number'])) {
                $errors[] = 'Student number format must be nn-nnnnn (e.g., 23-12345)';
            } else {
                $year = now()->year;
                $lastTwo = (int) substr($year, -2);

                $firstTwo = (int) substr($row['student_number'], 0, 2);

                if ($lastTwo - $firstTwo > 5) {
                    $row['year_level'] = 5;
                } else {
                    $row['year_level'] = $lastTwo - $firstTwo;
                }
            }
        }

        return $errors;
    }

    /**
     * Validate subject exists
     */
    protected function validateSubject(GradeImportRow $row, Collection $subjects): array
    {
        $errors = [];

        if (empty($row->subject_code)) {
            $errors[] = 'Subject code is required';
            return $errors;
        }

        $subject = $subjects->get($row->subject_code);

        if (!$subject) {
            $row->subject_name = null;
            $errors[] = 'Subject not found';
        } else {
            $row->subject_code = $subject->code;
            $row->subject_name = $subject->name;
        }

        return $errors;
    }

    /**
     * Validate unit type
     */
    protected function validateUnitType(GradeImportRow $row): array
    {
        $errors = [];

        if (empty($row->unit_type)) {
            $errors[] = 'Unit Type is required';
        } elseif (!in_array($row->unit_type, ['lec', 'lab'])) {
            $errors[] = 'Invalid Unit Type (should be "lec" or "lab")';
        }

        return $errors;
    }

    /**
     * Check for duplicate entries
     */
    protected function validateDuplicateEntry(GradeImportRow $row, GradeImport $gradeImport): array
    {
        $errors = [];

        if (empty($row->student_number) || empty($row->subject_code)) {
            return $errors; // Skip duplicate check if required fields are missing
        }

        // Find all rows with same key fields
        $duplicates = GradeImportRow::where('student_number', $row->student_number)
            ->where('subject_code', $row->subject_code)
            ->where('school_year', $row->school_year)
            ->where('semester', $row->semester)
            ->where('unit_type', $row->unit_type)
            ->where('id', '!=', $row->id)
            ->get();

        if ($duplicates->isNotEmpty()) {
            // Mark all duplicates as invalid
            foreach ($duplicates as $duplicate) {
                $duplicate->validity = 'invalid';
                $duplicate->errors = json_encode(['Duplicate entry for the same student and subject']);

                $student = Student::where('student_number', $duplicate->student_number)->first();

                Grade::where('student_id', $student->id ?? null)
                    ->where('subject_code', $duplicate->subject_code)
                    ->where('school_year', $duplicate->school_year)
                    ->where('semester', $duplicate->semester)
                    ->where('unit_type', $duplicate->unit_type)
                    ->delete();

                $duplicate->status = 'staged';

                $duplicate->save();
            }

            $errors[] = 'Duplicate entry for the same student and subject';
        }

        return $errors;
    }

    /**
     * Validate faculty is present
     */
    protected function validateFaculty(GradeImportRow $row): array
    {
        $errors = [];

        if (empty($row->faculty)) {
            $errors[] = 'Faculty is required';
        }

        return $errors;
    }

    /**
     * Validate credit unit
     */
    protected function validateCreditUnit(GradeImportRow $row): array
    {
        $errors = [];

        if (!isset($row->credit_unit) || !is_numeric($row->credit_unit)) {
            $errors[] = 'Invalid credit unit';
        }

        return $errors;
    }

    /**
     * Validate grade value
     */
    protected function validateGrade(GradeImportRow $row): array
    {
        $errors = [];

        if (!isset($row->grade) || !is_numeric($row->grade)) {
            $errors[] = 'Invalid grade';
            return $errors;
        }

        // Grade must be 0, between 1 and 3, or 5
        // DRP = -1, INC = 0
        if (
            $row->grade != -1 && $row->grade != 0 && ($row->grade < 1 || $row->grade > 3) &&
            $row->grade != 5
        ) {
            $errors[] = 'Grade must be INC or DRP or between 1 and 3, or 5.';
        }

        return $errors;
    }
}
