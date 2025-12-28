<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Imports\GradesImport;
use App\Models\GradeImport;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
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
            ->editColumn('status', function ($row) {
                $badges = [
                    'pending' => 'bg-warning',
                    'processing' => 'bg-info',
                    'completed' => 'bg-success',
                    'failed' => 'bg-danger',
                ];
                
                return '<span class="badge ' . ($badges[$row->status] ?? 'bg-secondary') . '">' 
                       . ucfirst($row->status) 
                       . '</span>';
            })
            ->rawColumns(['status'])
            ->make(true);
    }

    // public function store(Request $request)
    // {
    //     $validated = $request->validate([
    //         'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
    //     ]);

    //     $file = $request->file('file');

    //     $import = GradeImport::create([
    //         'user_id' => auth()->id(),
    //         'filename' => $file->getClientOriginalName(),
    //         'status' => 'pending',
    //     ]);

    //     try {
    //         Excel::import(new GradesImport($import->id), $file);
            
    //         $import->update([
    //             'total_rows' => $import->rows()->count(),
    //             'valid_rows' => $import->rows()->where('status', 'valid')->count(),
    //             'invalid_rows' => $import->rows()->where('status', 'invalid')->count(),
    //         ]);

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'File uploaded successfully',
    //             'import_id' => $import->id,
    //             'import' => $import
    //         ]);

    //     } catch (Exception $e) {
    //         $import->update([
    //             'status' => 'failed',
    //             'notes' => $e->getMessage()
    //         ]);

    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Upload failed: ' . $e->getMessage()
    //         ], 500);
    //     }
    // }
    
    public function store(Request $request)
    {   
        try {
            // FIXED VALIDATION - more flexible
            $validated = $request->validate([
                'file' => 'required|file|mimes:csv,xlsx,xls,txt|mimetypes:text/plain,text/csv,text/x-csv,application/csv,application/x-csv,text/comma-separated-values,text/x-comma-separated-values,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet|max:10240',
            ]);

            $file = $request->file('file');

            // Create import record
            $import = GradeImport::create([
                'user_id' => auth()->id(),
                'filename' => $file->getClientOriginalName(),
                'status' => 'pending',
            ]);

            Excel::import(new GradesImport($import->id), $file);
            
            $import->refresh();
            
            $rowsCount = $import->rows()->count();
            $validCount = $import->rows()->where('status', 'valid')->count();
            $invalidCount = $import->rows()->where('status', 'invalid')->count();
            
            $import->update([
                'total_rows' => $rowsCount,
                'valid_rows' => $validCount,
                'invalid_rows' => $invalidCount,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'File uploaded successfully',
                'import_id' => $import->id,
                'import' => $import->fresh()
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . implode(', ', $e->validator->errors()->all())
            ], 422);
            
        } catch (Exception $e) {
            if (isset($import)) {
                $import->update([
                    'status' => 'failed',
                    'notes' => $e->getMessage()
                ]);
            }

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

        $filename = $grade_import->filename;

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($rows) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, [
                'student_id',
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
                    $row->student_id,
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
        
    public function preview() {

    }

    public function commit() {
        
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
