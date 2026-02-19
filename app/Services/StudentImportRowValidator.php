<?php

namespace App\Services;

use App\Models\Grade;
use App\Models\GradeImport;
use App\Models\GradeImportRow;
use App\Models\Student;
use Illuminate\Support\Collection;

class GradeImportRowValidator
{
    /**
     * Validate a grade import row
     *
     * @param GradeImportRow $row
     * @param Collection $students Keyed by student_number
     * @param Collection $subjects Keyed by code
     * @param GradeImport $gradeImport
     * @return array Array of error messages
     */
    public function validateRow(
        GradeImportRow $row,
        Collection $students,
        Collection $subjects,
        GradeImport $gradeImport
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
    protected function validateStudent(GradeImportRow $row, Collection $students): array
    {
        $errors = [];

        if (empty($row->student_number)) {
            $errors[] = 'Student ID is required';
            return $errors;
        }

        $student = $students->get($row->student_number);

        if (!$student) {
            $errors[] = 'Student not found';
        } else {
            // Update the row with the confirmed student number
            $row->student_number = $student->student_number;
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

