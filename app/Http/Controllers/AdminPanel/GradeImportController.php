<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Models\GradeImport;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Maatwebsite\Excel\Excel;
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
            ->make(true);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        $file = $request->file('file');

        $import = GradeImport::create([
            'user_id' => auth()->id(),
            'filename' => $file->getClientOriginalName(),
            'status' => 'pending',
        ]);

        try {
            Excel::import(new GradeImport($import->id), $file);
            
            $import->update([
                'total_rows' => $import->rows()->count(),
                'valid_rows' => $import->rows()->whereNull('errors')->count(),
                'invalid_rows' => $import->rows()->whereNotNull('errors')->count(),
            ]);

            return redirect()
                ->route('grades.import.preview', $import)
                ->with('success', 'File uploaded successfully. Please review the data.');

        }
        catch (Exception $e) {
            $import->update([
                'status' => 'failed'
            ]);
        }
    }
    
    public function preview() {

    }

    public function commit() {
        
    }
}
