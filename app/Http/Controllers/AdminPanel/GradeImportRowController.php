<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Models\GradeImport;
use App\Models\GradeImportRow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class GradeImportRowController extends Controller
{
    //
    public function index($gradeImportId) {
        $decrypted = Crypt::decryptString($gradeImportId);
        $gradeImport = GradeImport::findOrFail($decrypted);

        return view('app.admin_panel.grade_import_management.grade_import_rows.index', [
            'gradeImportId' => Crypt::encryptString($gradeImport->id),
            'gradeImportName' => $gradeImport->filename,
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
            'student_id' => 'required|string|max:50',
            'subject_code' => 'required|string|max:50',
            'subject_name' => 'required|string|max:255',
            'unit_type' => 'required|string|max:100',
            'grade' => 'required|numeric|min:0|max:100',
            'faculty' => 'required|string|max:255',
            'credit_unit' => 'required|numeric|min:0|max:5',
        ]);

        $grade_import = GradeImport::findOrFail($decrypted);

        $validated['school_year'] = $grade_import->academic_period->year_start . '-' . $grade_import->academic_period->year_end;
        $validated['semester'] = $grade_import->academic_period->semester;

        $validated['validity'] = 'valid';
        $grade_import->valid_rows += 1;
        $grade_import->total_rows += 1;
        $grade_import->save();

        $validated['status'] = 'staged';

        $validated['raw_student_identifier'] = $validated['student_id'];

        $validated['grade_import_id'] = $decrypted;
        GradeImportRow::create($validated);
        return response()->json(['success' => true]);

        }
        catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
            ], 500);
        }
       
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
}
