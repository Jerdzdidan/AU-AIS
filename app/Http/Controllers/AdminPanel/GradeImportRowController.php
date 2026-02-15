<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Imports\GradesImport;
use App\Models\Grade;
use App\Models\GradeImport;
use App\Models\GradeImportRow;
use App\Models\Student;
use App\Models\Subject;
use App\Services\GradeImportRowValidator;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class GradeImportRowController extends Controller
{
    // Status Constants
    const STATUS_STAGED = 'staged';
    const STATUS_COMMITTED = 'committed';

    // Validity Constants
    const VALIDITY_VALID = 'valid';
    const VALIDITY_INVALID = 'invalid';

    // Unit Type Constants
    const UNIT_TYPE_LEC = 'lec';
    const UNIT_TYPE_LAB = 'lab';

    protected GradeImportRowValidator $validator;

    public function __construct(GradeImportRowValidator $validator)
    {
        $this->validator = $validator;
    }

    // Display the grade import rows index page
    public function index(string $gradeImportId)
    {
        try {
            $gradeImport = $this->findGradeImport($gradeImportId);

            // Validate all rows within a transaction
            DB::transaction(function () use ($gradeImport) {
                $this->validateAllRows($gradeImport);
            });

            // Update grade import statistics
            $this->updateGradeImportStatistics($gradeImport);

            $invalidCount = $gradeImport->rows()->where('validity', $this::VALIDITY_INVALID)->count();
            $stagedCount = $gradeImport->rows()->where('status', $this::STATUS_STAGED)->count();

            $allCommited = $gradeImport->rows()->where('status', $this::STATUS_STAGED)->count() === 0;

            return view('app.admin_panel.grade_import_management.grade_import_rows.index', [
                'gradeImportId' => Crypt::encryptString($gradeImport->id),
                'gradeImportName' => $gradeImport->filename,
                'valid' => $invalidCount === 0,
                'allCommited' => $allCommited,
                'hasStagedData' => $stagedCount > 0,
            ]);
        } catch (Exception $e) {
            Log::error('Error in grade import rows index', [
                'grade_import_id' => $gradeImportId,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'An error occurred while loading grade import rows.');
        }
    }

    // Get data for DataTables
    public function getData(Request $request, string $gradeImportId): JsonResponse
    {
        try {
            $gradeImport = $this->findGradeImport($gradeImportId);
            $gradeImportRows = $gradeImport->rows();
            $gradeImportRows->join('students', 'students.student_number', '=', 'grade_import_rows.student_number')
                ->join('programs', 'programs.id', '=', 'students.program_id')
                ->select('grade_import_rows.*', 'programs.code as program_code');

            if ($request->filled('status') && $request->status !== 'All') {
                $gradeImportRows->where('status', $request->status);
            }

            if ($request->filled('validity') && $request->validity !== 'All') {
                $gradeImportRows->where('validity', $request->validity);
            }

            if ($request->filled('program') && $request->program !== 'All') {
                $programId = $request->program;

                // Join students table to filter by program_id using student_number
                $gradeImportRows->where('students.program_id', $programId)
                    ->select('grade_import_rows.*', 'programs.code as program_code');
            }

            $gradeImportRows = $gradeImportRows->get();

            return datatables()->of($gradeImportRows)
                ->editColumn('id', fn($row) => Crypt::encryptString($row->id))
                ->addColumn('program', fn($row) => $row->program_code)
                ->make(true);
        } catch (Exception $e) {
            Log::error('Error fetching grade import row data', [
                'grade_import_id' => $gradeImportId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error fetching data'
            ], 500);
        }
    }

    // Store a new grade import row
    public function store(Request $request, string $gradeImportId): JsonResponse
    {
        try {
            $gradeImport = $this->findGradeImport($gradeImportId);

            // Validate input
            $validated = $this->validateRequest($request);

            // Verify student and subject exist
            $student = $this->findStudentOrFail($validated['student_number']);
            $subject = $this->findSubjectOrFail($validated['subject_code']);

            // Prepare data for creation
            $data = $this->prepareRowData($validated, $gradeImport, $subject);

            DB::transaction(function () use ($gradeImport, $data) {
                // Create the new row
                $newRow = GradeImportRow::create($data);

                // Validate all rows including the new one
                $this->validateAllRows($gradeImport);

                // Update statistics
                $this->updateGradeImportStatistics($gradeImport);
            });

            $allValid = $gradeImport->rows()->where('validity', $this::VALIDITY_INVALID)->count() === 0;
            $allCommited = $gradeImport->rows()->where('status', $this::STATUS_STAGED)->count() === 0;

            return response()->json(['success' => true, 'allValid' => $allValid, 'allCommited' => $allCommited]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
            ], 422);
        } catch (Exception $e) {
            Log::error('Error creating grade import row', [
                'grade_import_id' => $gradeImportId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function import(Request $request, string $gradeImportId): JsonResponse
    {
        try {
            $validated = $request->validate([
                'file' => 'required|file|mimes:csv,xlsx,xls,txt|mimetypes:text/plain,text/csv,text/x-csv,application/csv,application/x-csv,text/comma-separated-values,text/x-comma-separated-values,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet|max:10240',
            ]);

            $file = $validated['file'];
            $gradeImport = $this->findGradeImport($gradeImportId);
            Excel::import(new GradesImport($gradeImport->id), $file);

            // Validate all rows after import
            DB::transaction(function () use ($gradeImport) {
                $this->validateAllRows($gradeImport);
                $this->updateGradeImportStatistics($gradeImport);
            });

            $allValid = $gradeImport->rows()->where('validity', $this::VALIDITY_INVALID)->count() === 0;
            $allCommited = $gradeImport->rows()->where('status', $this::STATUS_STAGED)->count() === 0;

            return response()->json([
                'success' => true,
                'message' => 'File imported successfully',
                'allValid' => $allValid,
                'allCommited' => $allCommited
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
            ], 422);
        } catch (Exception $e) {
            Log::error('Error importing grade import row', [
                'grade_import_id' => $gradeImportId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Get grade import row for editing
    public function edit(string $gradeImportRowId): JsonResponse
    {
        try {
            $row = $this->findGradeImportRow($gradeImportRowId);

            return response()->json([
                'id' => Crypt::encryptString($row->id),
                'student_number' => $row->student_number,
                'subject_code' => $row->subject_code,
                'unit_type' => $row->unit_type,
                'grade' => $row->grade,
                'faculty' => $row->faculty,
                'credit_unit' => $row->credit_unit
            ]);
        } catch (Exception $e) {
            Log::error('Error fetching grade import row for edit', [
                'grade_import_row_id' => $gradeImportRowId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Row not found'
            ], 404);
        }
    }

    // Update a grade import row
    public function update(Request $request, string $gradeImportRowId): JsonResponse
    {
        try {
            $row = $this->findGradeImportRow($gradeImportRowId);

            // Validate input
            $validated = $this->validateRequest($request);

            // Verify student and subject exist
            $student = $this->findStudentOrFail($validated['student_number']);
            $subject = $this->findSubjectOrFail($validated['subject_code']);

            DB::transaction(function () use ($row, $validated, $subject) {
                // Update the row
                $row->update($validated);

                // Update subject name
                $row->subject_name = $subject->name;
                $row->save();

                // Validate all rows in the import
                $this->validateAllRows($row->gradeImport);

                // Update statistics
                $this->updateGradeImportStatistics($row->gradeImport);
            });

            $gradeImport = $row->fresh()->gradeImport;
            $allValid = $gradeImport->rows()->where('validity', $this::VALIDITY_INVALID)->count() === 0;
            $allCommited = $gradeImport->rows()->where('status', $this::STATUS_STAGED)->count() === 0;

            return response()->json([
                'success' => true,
                'allValid' => $allValid,
                'allCommited' => $allCommited
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
            ], 422);
        } catch (Exception $e) {
            Log::error('Error updating grade import row', [
                'grade_import_row_id' => $gradeImportRowId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Delete a grade import row
    public function destroy(string $gradeImportRowId): JsonResponse
    {
        try {
            $row = $this->findGradeImportRow($gradeImportRowId);
            $gradeImport = $row->gradeImport;

            DB::transaction(function () use ($row, $gradeImport) {
                // Update counts before deletion
                if ($row->validity === $this::VALIDITY_VALID) {
                    $gradeImport->valid_rows = max(0, $gradeImport->valid_rows - 1);
                } elseif ($row->validity === $this::VALIDITY_INVALID) {
                    $gradeImport->invalid_rows = max(0, $gradeImport->invalid_rows - 1);
                }

                $gradeImport->total_rows = max(0, $gradeImport->total_rows - 1);
                $gradeImport->save();

                $row->delete();

                // Validate all remaining rows to ensure consistency
                $this->validateAllRows($gradeImport);
                // Update statistics after validation
                $this->updateGradeImportStatistics($gradeImport);
            });

            $allValid = $gradeImport->rows()->where('validity', $this::VALIDITY_INVALID)->count() === 0;
            $allCommited = $gradeImport->rows()->where('status', $this::STATUS_STAGED)->count() === 0;

            return response()->json([
                'success' => true,
                'allValid' => $allValid,
                'allCommited' => $allCommited,
                'message' => 'Grade data record deleted successfully.'
            ]);
        } catch (Exception $e) {
            Log::error('Error deleting grade import row', [
                'grade_import_row_id' => $gradeImportRowId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error deleting record'
            ], 500);
        }
    }

    // Commit a single row to grades table
    public function commitRow(string $gradeImportRowId): JsonResponse
    {
        try {
            $row = $this->findGradeImportRow($gradeImportRowId);

            if ($row->validity !== $this::VALIDITY_VALID) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot commit invalid row.'
                ], 400);
            }

            DB::transaction(function () use ($row) {
                $student = Student::where('student_number', $row->student_number)->firstOrFail();

                Grade::create([
                    'student_id' => $student->id,
                    'subject_code' => $row->subject_code,
                    'subject_name' => $row->subject_name,
                    'unit_type' => $row->unit_type,
                    'school_year' => $row->school_year,
                    'semester' => $row->semester,
                    'faculty' => $row->faculty,
                    'credit_unit' => $row->credit_unit,
                    'grade' => $row->grade,
                    'grade_import_id' => $row->grade_import_id,
                    'grade_import_row_id' => $row->id,
                ]);

                $row->status = $this::STATUS_COMMITTED;
                $row->save();
            });

            return response()->json([
                'success' => true,
                'message' => 'Grade data row committed successfully.'
            ]);
        } catch (Exception $e) {
            Log::error('Error committing grade import row', [
                'grade_import_row_id' => $gradeImportRowId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error committing row'
            ], 500);
        }
    }

    // Commit all valid staged rows
    public function commitAll(string $gradeImportId): JsonResponse
    {
        try {
            $gradeImport = $this->findGradeImport($gradeImportId);

            $rows = $gradeImport->rows()
                ->where('status', $this::STATUS_STAGED)
                ->get();

            // Validate before committing
            if ($rows->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No staged rows to commit.'
                ], 400);
            }

            $invalidCount = $rows->where('validity', $this::VALIDITY_INVALID)->count();
            if ($invalidCount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot commit all rows. There are invalid rows present.'
                ], 400);
            }

            // Commit all rows
            DB::transaction(function () use ($rows, $gradeImport) {
                // Eager load students to avoid N+1 queries
                $studentNumbers = $rows->pluck('student_number')->unique();
                $students = Student::whereIn('student_number', $studentNumbers)
                    ->get()
                    ->keyBy('student_number');

                foreach ($rows as $row) {
                    $student = $students->get($row->student_number);

                    if (!$student) {
                        throw new Exception("Student not found: {$row->student_number}");
                    }

                    Grade::create([
                        'student_id' => $student->id,
                        'subject_code' => $row->subject_code,
                        'subject_name' => $row->subject_name,
                        'unit_type' => $row->unit_type,
                        'school_year' => $row->school_year,
                        'semester' => $row->semester,
                        'faculty' => $row->faculty,
                        'credit_unit' => $row->credit_unit,
                        'grade' => $row->grade,
                        'grade_import_id' => $row->grade_import_id,
                        'grade_import_row_id' => $row->id,
                    ]);

                    $row->status = $this::STATUS_COMMITTED;
                    $row->save();
                }

                // Update grade import status
                $stagedCount = $gradeImport->rows()->where('status', $this::STATUS_STAGED)->count();
                if ($stagedCount === 0) {
                    $gradeImport->status = $this::STATUS_COMMITTED;
                }

                $gradeImport->processed_at = now();
                $gradeImport->save();
            });

            return response()->json([
                'success' => true,
                'message' => 'All staged grade data rows committed successfully.'
            ]);
        } catch (Exception $e) {
            Log::error('Error committing all grade import rows', [
                'grade_import_id' => $gradeImportId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error committing rows: ' . $e->getMessage()
            ], 500);
        }
    }

    // Uncommit a row (revert from committed to staged)
    public function unCommit(string $gradeImportRowId): JsonResponse
    {
        try {
            $row = $this->findGradeImportRow($gradeImportRowId);

            DB::transaction(function () use ($row) {
                Grade::where('grade_import_row_id', $row->id)->delete();

                $row->status = $this::STATUS_STAGED;
                $row->save();
            });

            return response()->json([
                'success' => true,
                'message' => 'Grade data row uncommitted successfully.'
            ]);
        } catch (Exception $e) {
            Log::error('Error uncommitting grade import row', [
                'grade_import_row_id' => $gradeImportRowId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error uncommitting row'
            ], 500);
        }
    }

    public function uncommitAll(string $gradeImportId): JsonResponse
    {
        try {
            $gradeImport = $this->findGradeImport($gradeImportId);

            DB::transaction(function () use ($gradeImport) {
                Grade::where('grade_import_id', $gradeImport->id)->delete();

                $gradeImport->rows()
                    ->where('status', $this::STATUS_COMMITTED)
                    ->update(['status' => $this::STATUS_STAGED]);
            });

            $gradeImport->processed_at = now();
            $gradeImport->save();

            // Validate all remaining rows to ensure consistency
            $this->validateAllRows($gradeImport);
            // Update statistics after validation
            $this->updateGradeImportStatistics($gradeImport);

            return response()->json([
                'success' => true,
                'message' => 'All staged grade data rows committed successfully.'
            ]);
        } catch (Exception $e) {
            Log::error('Error committing all grade import rows', [
                'grade_import_id' => $gradeImportId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error committing rows: ' . $e->getMessage()
            ], 500);
        }
    }

    // Fetch validation errors for a row
    public function fetchErrors(string $gradeImportRowId): JsonResponse
    {
        try {
            $row = $this->findGradeImportRow($gradeImportRowId);

            $errors = $row->errors ? json_decode($row->errors, true) : [];

            return response()->json([
                'success' => true,
                'messages' => $errors
            ]);
        } catch (Exception $e) {
            Log::error('Error fetching errors for grade import row', [
                'grade_import_row_id' => $gradeImportRowId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'errors' => []
            ], 500);
        }
    }

    /**
     * Helper Methods
     */

    // Find grade import by encrypted ID
    protected function findGradeImport(string $encryptedId): GradeImport
    {
        $decrypted = Crypt::decryptString($encryptedId);
        return GradeImport::findOrFail($decrypted);
    }

    // Find grade import row by encrypted ID
    protected function findGradeImportRow(string $encryptedId): GradeImportRow
    {
        $decrypted = Crypt::decryptString($encryptedId);
        return GradeImportRow::findOrFail($decrypted);
    }

    // Validate all rows in a grade import
    protected function validateAllRows(GradeImport $gradeImport): void
    {
        $rows = $gradeImport->rows()->get();

        // Eager load students and subjects to avoid N+1 queries
        $studentNumbers = $rows->pluck('student_number')->unique()->filter();
        $subjectCodes = $rows->pluck('subject_code')->unique()->filter();

        $students = Student::whereIn('student_number', $studentNumbers)
            ->get()
            ->keyBy('student_number');

        $subjects = Subject::whereIn('code', $subjectCodes)
            ->get()
            ->keyBy('code');

        foreach ($rows as $row) {
            $errors = $this->validator->validateRow(
                $row,
                $students,
                $subjects,
                $gradeImport
            );

            $row->validity = empty($errors) ? $this::VALIDITY_VALID : $this::VALIDITY_INVALID;
            $row->errors = json_encode($errors);
            $row->save();
        }
    }

    // Update grade import statistics
    protected function updateGradeImportStatistics(GradeImport $gradeImport): void
    {
        $gradeImport->valid_rows = $gradeImport->rows()
            ->where('validity', $this::VALIDITY_VALID)
            ->count();

        $gradeImport->invalid_rows = $gradeImport->rows()
            ->where('validity', $this::VALIDITY_INVALID)
            ->count();

        $gradeImport->total_rows = $gradeImport->rows()->count();

        $gradeImport->save();
    }

    // Validate request data
    protected function validateRequest(Request $request): array
    {
        $request = $request->validate([
            'student_number' => 'required|string|max:50',
            'subject_code' => 'required|string|max:50',
            'unit_type' => 'required|string|in:' . $this::UNIT_TYPE_LEC . ',' . $this::UNIT_TYPE_LAB,
            'grade' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if ($value != "DRP" && $value != "INC" && ($value < 1 || $value > 3) && $value != 5) {
                        $fail('The ' . $attribute . ' must be INC or DRP or between 1 and 3, or 5.');
                    }
                }
            ],
            'faculty' => 'required|string|max:255',
            'credit_unit' => 'required|numeric|min:0|max:5',
        ]);

        $grade = $request['grade'];

        if ($grade == "DRP") {
            $request['grade'] = -1;
        } else if ($grade == "INC") {
            $request['grade'] = 0;
        } else {
            $request['grade'] = round((float) $request['grade'], 2);
        }

        return $request;
    }

    // Find student or throw exception
    protected function findStudentOrFail(string $studentNumber): Student
    {
        $student = Student::where('student_number', $studentNumber)->first();

        if (!$student) {
            throw ValidationException::withMessages([
                'student_number' => ['Student with this number does not exist.']
            ]);
        }

        return $student;
    }

    // Find subject or throw exception
    protected function findSubjectOrFail(string $subjectCode): Subject
    {
        $subject = Subject::where('code', $subjectCode)->first();

        if (!$subject) {
            throw ValidationException::withMessages([
                'subject_code' => ['Subject with this code does not exist.']
            ]);
        }

        return $subject;
    }

    // Prepare row data for creation
    protected function prepareRowData(array $validated, GradeImport $gradeImport, Subject $subject): array
    {
        return array_merge($validated, [
            'school_year' => $gradeImport->academic_period->year_start . '-' .
                $gradeImport->academic_period->year_end,
            'semester' => $gradeImport->academic_period->semester,
            'subject_name' => $subject->name,
            'validity' => $this::VALIDITY_VALID,
            'status' => $this::STATUS_STAGED,
            'grade_import_id' => $gradeImport->id,
        ]);
    }
}
