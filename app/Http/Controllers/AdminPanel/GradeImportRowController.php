<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\GradeImport;
use App\Models\GradeImportRow;
use App\Models\Student;
use App\Models\Subject;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use League\Config\Exception\ValidationException;

class GradeImportRowController extends Controller
{
    //
    public function index($gradeImportId) {
        $decrypted = Crypt::decryptString($gradeImportId);
        $gradeImport = GradeImport::findOrFail($decrypted);

        $invalid = $gradeImport->rows()->where('validity', 'invalid')->count();
        $staged = $gradeImport->rows()->where('status', 'staged')->count();

        $rows = $gradeImport->rows()->get();

        foreach ($rows as $row) {
            $student = null;
            $subject = null;

            $row->errors = null;

            $errors = [];

            if (isset($row['student_number'])) {
                $student = Student::where('student_number', $row['student_number'])
                    ->first();
                if (!$student) {
                    $errors[] = 'Student not found';
                }
                else {
                    $row->student_number = $student->student_number;
                }
            } else {
                $errors[] = 'Student ID is required';
            }

            if (isset($row['subject_code'])) {
                $subject = Subject::where('code', $row['subject_code'])->first();
                
                if (!$subject) {
                    $row->subject_name = null;
                    $errors[] = 'Subject not found';
                }
                else {
                    $row->subject_code = $subject->code;
                    $row->subject_name = $subject->name;
                }
            } else {
                $errors[] = 'Subject code is required';
            }

            if (!isset($row['unit_type'])) {
                $errors[] = 'Unit Type is required';
            } else {
                if ($row['unit_type'] !== 'lec' && $row['unit_type'] !== 'lab') {
                    $errors[] = 'Invalid Unit Type (should be "lec" or "lab")';
                }
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
            else
            {
                if ($row['grade'] != 0 && ($row['grade'] < 1 || $row['grade'] > 3) && $row['grade'] != 5) {
                    $errors[] = 'Grade must be 0, between 1 and 3, or 5';
                }
            }

            $row->validity =  empty($errors) ? 'valid' : 'invalid';

            $row->errors = json_encode($errors);
            $row->save();
        }

        $gradeImport->valid_rows = $gradeImport->rows()->where('validity', 'valid')->count();
        $gradeImport->invalid_rows = $gradeImport->rows()->where('validity', 'invalid')->count();
        $gradeImport->save();

        return view('app.admin_panel.grade_import_management.grade_import_rows.index', [
            'gradeImportId' => Crypt::encryptString($gradeImport->id),
            'gradeImportName' => $gradeImport->filename,
            'valid' => $invalid <= 0 ? true : false,
            'hasStagedData' => $staged > 0 ? true : false,
        ]);
    }

    public function getData($gradeImportId)
    {
        $decrypted = Crypt::decryptString($gradeImportId);
        $gradeImport = GradeImport::findOrFail($decrypted);

        $gradeImportRows = $gradeImport->rows;

        return datatables()->of($gradeImportRows)
            ->editColumn('id', function ($row) {
                return Crypt::encryptString($row->id);
            })
            ->make(true);
    }

    public function store(Request $request, $gradeImportId)
    {
        try{
            $decrypted = Crypt::decryptString($gradeImportId);

            $validated = $request->validate([
                'student_number' => 'required|string|max:50',
                'subject_code' => 'required|string|max:50',
                'unit_type' => 'required|string|max:100',
                'grade' => [
                    'required', 
                    'numeric', 
                    'min:0', 
                    'max:100',
                    function ($attribute, $value, $fail) {
                        if ($value != 0 && ($value < 1 || $value > 3) && $value != 5) {
                            $fail('The '.$attribute.' must be 0, between 1 and 3, or 5.');
                        }
                    }
                ],
                'faculty' => 'required|string|max:255',
                'credit_unit' => 'required|numeric|min:0|max:5',
            ]);

            $student = Student::where('student_number', $validated['student_number'])->first();
            if (!$student) {
                return response()->json([
                    'success' => false,
                    'errors' => ['student_number' => ['Student with this number does not exist.']],
                ], 422);
            }

            $subject = Subject::where('code', $validated['subject_code'])->first();
            if (!$subject) {
                return response()->json([
                    'success' => false,
                    'errors' => ['subject_code' => ['Subject with this code does not exist.']],
                ], 422);
            }

            $grade_import = GradeImport::findOrFail($decrypted);

            $validated['school_year'] = $grade_import->academic_period->year_start . '-' . $grade_import->academic_period->year_end;
            $validated['semester'] = $grade_import->academic_period->semester;
            $validated['subject_name'] = $subject->name;

            $validated['validity'] = 'valid';
            $grade_import->valid_rows += 1;
            $grade_import->total_rows += 1;
            $grade_import->save();

            $validated['status'] = 'staged';

            $validated['grade_import_id'] = $decrypted;
            GradeImportRow::create($validated);
            return response()->json(['success' => true]);

        }
        catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
            ], 500);
        }
       
    }

    public function edit($gradeImportRowId) {
        $decrypted = Crypt::decryptString($gradeImportRowId);
        $row = GradeImportRow::findOrFail($decrypted);

        return response()->json([
            'id' => Crypt::encryptString($row->id),
            'student_number' => $row->student_number,
            'subject_code' => $row->subject_code,
            'unit_type' => $row->unit_type,
            'grade' => $row->grade,
            'faculty' => $row->faculty,
            'credit_unit' => $row->credit_unit
        ]);
    }

    public function update(Request $request, $gradeImportRowId)
    {
        $decrypted = Crypt::decryptString($gradeImportRowId);
        $row = GradeImportRow::findOrFail($decrypted);

        $validated = $request->validate([
            'student_number' => 'required|string|max:50',
            'subject_code' => 'required|string|max:50',
            'unit_type' => 'required|string|max:100',
            'grade' => [
                'required', 
                'numeric', 
                'min:0', 
                'max:100',
                function ($attribute, $value, $fail) {
                    if ($value != 0 && ($value < 1 || $value > 3) && $value != 5) {
                        $fail('The '.$attribute.' must be 0, between 1 and 3, or 5.');
                    }
                }
            ],
            'faculty' => 'required|string|max:255',
            'credit_unit' => 'required|numeric|min:0|max:5',
        ]);

        $row->update($validated);

        $valid = true;

        if (isset($row['student_number'])) {
            $student = Student::where('student_number', $row['student_number'])
                ->first();
            if (!$student) {
                $valid = false;
            }
        } else {
            $valid = false;
        }

        if (isset($row['subject_code'])) {
            $subject = Subject::where('code', $row['subject_code'])->first();
            
            if (!$subject) {
                $valid = false;
            }
            else {
                $row->subject_name = $subject->name;
                $row->save();
            }
        } else {
            $valid = false;
        }

        if (!isset($row['unit_type'])) {
            $valid = false;
        }

        if (!isset($row['faculty'])) {
            $valid = false;
        }

        if (!isset($row['credit_unit']) || !is_numeric($row['credit_unit'])) {
            $valid = false;
        }
        
        if (!isset($row['grade']) || !is_numeric($row['grade'])) {
            $valid = false;
        }
        else
        {
            if ($row['grade'] != 0 && ($row['grade'] < 1 || $row['grade'] > 3) && $row['grade'] != 5) {
                $valid = false;
            }
        }

        if ($valid){
            if ($row->validity !== 'valid') {
                $row->validity = 'valid';
                $row->save();
            }
        } else {
            if ($row->validity !== 'invalid') {
                $row->validity = 'invalid';
                $row->save();
            }
        }

        $grade_import = $row->gradeImport;
        $grade_import->valid_rows = $grade_import->rows()->where('validity', 'valid')->count();
        $grade_import->invalid_rows = $grade_import->rows()->where('validity', 'invalid')->count();
        $grade_import->save();

        $allValid = $grade_import->rows()->where('validity', 'invalid')->count() <= 0 ? true : false;

        return response()->json(['success' => true, 'allValid' => $allValid]);
    }

    public function destroy($gradeImportRowId) {
        $decrypted = Crypt::decryptString($gradeImportRowId);
        $row = GradeImportRow::findOrFail($decrypted);

        $gradeImport = $row->gradeImport;

        DB::beginTransaction();
            if ($row->validity === 'valid') {
                $gradeImport->valid_rows = max(0, $gradeImport->valid_rows - 1);
                $gradeImport->save();
            } elseif ($row->validity === 'invalid') {
                $gradeImport->invalid_rows = max(0, $gradeImport->invalid_rows - 1);
                $gradeImport->save();
            }

            $gradeImport->total_rows = max(0, $gradeImport->total_rows - 1);
            $gradeImport->save();

            $row->delete();
        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Grade data record deleted successfully.'
        ]);
    }

    public function commitRow($gradeImportRowId) {
        $decrypted = Crypt::decryptString($gradeImportRowId);
        $row = GradeImportRow::findOrFail($decrypted);

        $student = Student::where('student_number', $row->student_number)->first();

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
        ]);

        $row->status = 'committed';
        $row->save();

        return response()->json([
            'success' => true,
            'message' => 'Grade data row committed successfully.'
        ]);
    }

    public function commitAll($gradeImportId) {
        $decrypted = Crypt::decryptString($gradeImportId);
        $gradeImport = GradeImport::findOrFail($decrypted);

        $rows = $gradeImport->rows()->where('status', 'staged')->get();

        $invalid = $rows->where('validity', 'invalid')->count();


        if ($invalid > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot commit all rows. There are invalid rows present.'
            ], 400);
        }
        else{
            if ($rows->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No staged rows to commit.'
                ], 400);
            }
            else{
                DB::beginTransaction();
                    foreach ($rows as $row) {
                        $student = Student::where('student_number', $row->student_number)->first();

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

                        $row->status = 'committed';
                        $row->save();
                    }

                    $stagedRows = $gradeImport->rows()->where('status', 'staged')->count();
                    if ($stagedRows === 0) {
                        $gradeImport->status = 'committed';
                    }

                    $gradeImport->processed_at = now();
                    $gradeImport->save();
                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'All staged grade data rows committed successfully.'
                ]);
            }
        }
        
    }

    public function unCommit($gradeImportRowId) {
        $decrypted = Crypt::decryptString($gradeImportRowId);
        $row = GradeImportRow::findOrFail($decrypted);

        Grade::where('grade_import_row_id', $row->id)->delete();

        $row->status = 'staged';
        $row->save();

        return response()->json([
            'success' => true,
            'message' => 'Grade data row uncommitted successfully.'
        ]);
    }

    public function fetchErrors($gradeImportRowId) {
        try {
            
            $decrypted = Crypt::decryptString($gradeImportRowId);
            $row = GradeImportRow::findOrFail($decrypted);
            
            $errors = $row->errors;

            return response()->json([
                'success' => true,
                'messages' => $errors
            ]);
        } catch (Exception $e) {
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'errors' => []
            ], 500);
        }
    }
}
