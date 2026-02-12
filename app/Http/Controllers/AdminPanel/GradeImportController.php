<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Imports\GradesImport;
use App\Models\GradeImport;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class GradeImportController extends Controller
{
    //
    public function index() {
        return view('app.admin_panel.grade_import_management.index');
    }

    public function getData()
    {
        $grade_imports = GradeImport::get();
        
        return DataTables::of($grade_imports)
            ->editColumn('id', function ($row) {
                return Crypt::encryptString($row->id);
            })
            ->addColumn('academic_period_name', function ($row) {
                return $row->academic_period ? $row->academic_period->name : 'N/A';
            })
            ->rawColumns(['status'])
            ->make(true);
    }
    
    public function store(Request $request)
    {   
        try {
            // FIXED VALIDATION - more flexible
            $validated = $request->validate([
                'file' => 'required|file|mimes:csv,xlsx,xls,txt|mimetypes:text/plain,text/csv,text/x-csv,application/csv,application/x-csv,text/comma-separated-values,text/x-comma-separated-values,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet|max:10240',
                'academic_period_id' => 'required|exists:academic_periods,id|unique:grade_imports,academic_period_id,NULL,id,user_id,' . auth()->id(),
            ], [
                'academic_period_id.required' => 'The academic period field is required.',
                'academic_period_id.unique' => 'A grade import for this academic period already exists.',
            ]);

            $file = $request->file('file');
        
            // Check if filename already exists
            $existingImport = GradeImport::where('filename', pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
                ->first();
                
            if ($existingImport) {
                return response()->json([
                    'errors' => [
                        'filename' => ['A file with this name has already been imported.']
                    ],
                ], 422);
            }

            DB::beginTransaction();

            // Create import record
            $import = GradeImport::create([
                'user_id' => auth()->id(),
                'filename' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                'academic_period_id' => $validated['academic_period_id'],
                'status' => 'pending',
            ]);

            Excel::import(new GradesImport($import->id), $file);
            
            $import->refresh();
            
            $rowsCount = $import->rows()->count();
            $validCount = $import->rows()->where('validity', 'valid')->count();
            $invalidCount = $import->rows()->where('validity', 'invalid')->count();
            
            $import->update([
                'total_rows' => $rowsCount,
                'valid_rows' => $validCount,
                'invalid_rows' => $invalidCount,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'File uploaded successfully',
                'import_id' => $import->id,
                'import' => $import->fresh()
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . implode(', ', $e->validator->errors()->all()),
                'errors' => $e->validator->errors()->toArray()
            ], 422);
            
        } catch (Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Upload failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function download($id) {
        $decrypted = Crypt::decryptString($id);
        $grade_import = GradeImport::findOrFail($decrypted);

        $rows = $grade_import->rows;

        $filename = $grade_import->filename . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($rows) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, [
                'student_number',
                'subject_code',
                'subject_name',
                'unit_type',
                'school_year',
                'semester',
                'faculty',
                'credit_unit',
                'grade',
            ]);
            
            // CSV Data
            foreach ($rows as $row) {
                fputcsv($file, [
                    $row->student_number,
                    $row->subject_code,
                    $row->subject_name,
                    $row->unit_type,
                    $row->school_year,
                    $row->semester,
                    $row->faculty,
                    $row->credit_unit,
                    $row->grade,
                ]);
            }
            
            fclose($file);
        };
    
        return response()->stream($callback, 200, $headers);
    }

    public function edit($gradeImportId) 
    {
        $decrypted = Crypt::decryptString($gradeImportId);

        $grade_import = GradeImport::findOrFail($decrypted);

        return response()->json([
            'id' => Crypt::encryptString($grade_import->id),
            'filename' => $grade_import->filename,
            'academic_period_id' => $grade_import->academic_period_id,
        ]);
    }

    public function update(Request $request, $gradeImportId)
    {
        $decrypted = Crypt::decryptString($gradeImportId);
        $grade_import = GradeImport::findOrFail($decrypted);

        $validated = $request->validate([
            'filename' => 'required|string|max:255|unique:grade_imports,filename,' . $decrypted,
            'academic_period_update_id' => 'required|exists:academic_periods,id|unique:grade_imports,academic_period_id,' . $decrypted . ',id,user_id,' . auth()->id(),
        ],
        [
            'academic_period_update_id.required' => 'The academic period field is required.',
            'academic_period_update_id.unique' => 'A grade import for this academic period already exists.',
        ]);

        $grade_import->update([
            'filename' => $validated['filename'],
            'academic_period_id' => $validated['academic_period_update_id'],
        ]);

        return response()->json(['success' => true]);
    }

    public function destroy($gradeImport) {
        $decrypted = Crypt::decryptString($gradeImport);
        GradeImport::findOrFail($decrypted)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Grade import record deleted successfully.'
        ]);
    }
}
