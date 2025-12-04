<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Models\Curriculum;
use App\Models\Subject;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class SubjectController extends Controller
{
    //

    public function index($curriculum_id)
    {
        $decrypted = Crypt::decryptString($curriculum_id);
        $curriculum = Curriculum::findOrFail($decrypted);

        return view('app.admin_panel.curriculum_management.subject_management.index', [
            'curriculum_id' => Crypt::encryptString($curriculum->id),
            'curriculum_name' => $curriculum->program->code . ' - Curriculum',
        ]);
    }

    public function getData(Request $request, $curriculum_id)
    {
        $decrypted = Crypt::decryptString($curriculum_id);
        $subjects = Subject::where('curriculum_id', $decrypted)->select([
            'id',
            'code',
            'name',
            'year_level',
            'semester',
            'subject_category',
            'lec_units',
            'lab_units',
            'is_active',
            'prerequisites',
        ]);

        if ($request->filled('status') && $request->status !== 'All') {
            if ($request->status === 'Active') {
                $subjects->where('is_active', true);
            } elseif ($request->status === 'Inactive') {
                $subjects->where('is_active', false);
            }
        }
        
        return DataTables::of($subjects)
            ->editColumn('id', function ($row) {
                return Crypt::encryptString($row->id);
            })
            ->make(true);
    }

    public function getStats($curriculum_id)
{
        // Decrypt the curriculum_id first
        $decrypted = Crypt::decryptString($curriculum_id);
        
        return response()->json([
            'total' => Subject::where('curriculum_id', $decrypted)->count(),
            'total_units' => Subject::where('curriculum_id', $decrypted)->sum('lec_units') + Subject::where('curriculum_id', $decrypted)->sum('lab_units'),
            'active' => Subject::where('curriculum_id', $decrypted)->where('is_active', true)->count(),
            'inactive' => Subject::where('curriculum_id', $decrypted)->where('is_active', false)->count(),
        ]);
    }

    public function store(Request $request, $curriculum_id)
    {
        $decrypted = Crypt::decryptString($curriculum_id);

        $validated = $request->validate([
            'code' => 'required|string|max:50',
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('subjects')
                    ->where(fn($q) => $q->where('curriculum_id', $decrypted)),
            ],
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('subjects')
                    ->where(fn($q) => $q->where('curriculum_id', $decrypted)),
            ],
            'year_level' => 'nullable|integer|min:1|max:5',
            'semester' => 'required|string|max:50',
            'subject_category' => 'required|string|max:100',
            'lec_units' => 'required|numeric|min:0|max:5',
            'lab_units' => 'required|numeric|min:0|max:5',
            'prerequisites' => 'nullable|string',
        ], [
            'code.unique' => 'The subject code has already been taken for this curriculum.',
            'name.unique' => 'The subject name has already been taken for this curriculum.',
        ]);

        if ($validated['lec_units'] == 0 && $validated['lab_units'] == 0) {
            return response()->json([
                'errors' => [
                    'lec_units' => ['Lecture and lab units cannot both be zero.']
                ]
            ], 422);
        }

        if ($validated['subject_category'] == "MINOR") {
            $validated['year_level'] = null; 
            $validated['semester'] = null;
        }

        $validated['curriculum_id'] = $decrypted;
        Subject::create($validated);
        return response()->json(['success' => true]);
    }

    public function edit($id) 
    {
        $decrypted = Crypt::decryptString($id);

        $subject = Subject::findOrFail($decrypted);
        return response()->json([
            'id' => Crypt::encryptString($subject->id),
            'code' => $subject->code,
            'name' => $subject->name,
            'year_level' => $subject->year_level,
            'semester' => trim($subject->semester),
            'subject_category' => $subject->subject_category,
            'lec_units' => $subject->lec_units,
            'lab_units' => $subject->lab_units,
            'prerequisites' => $subject->prerequisites,
        ]);
    }

    public function update(Request $request, $id)
    {
        $decrypted = Crypt::decryptString($id);
        $subject = Subject::findOrFail($decrypted);

        $validated = $request->validate([
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('subjects')
                    ->ignore($subject->id)
                    ->where(fn($q) => $q->where('curriculum_id', $subject->curriculum_id)),
            ],
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('subjects')
                    ->ignore($subject->id)
                    ->where(fn($q) => $q->where('curriculum_id', $subject->curriculum_id)),
            ],
            'year_level' => 'nullable|integer|min:1|max:5',
            'semester' => 'required|string|max:50',
            'subject_category' => 'required|string|max:100',
            'lec_units' => 'required|numeric|min:0|max:5',
            'lab_units' => 'required|numeric|min:0|max:5',
            'prerequisites' => 'nullable|string',
        ], [
            'code.unique' => 'This subject code already exists in this curriculum.',
            'name.unique' => 'This subject name already exists in this curriculum.',
        ]);

        if ($validated['lec_units'] == 0 && $validated['lab_units'] == 0) {
            return response()->json([
                'errors' => [
                    'lec_units' => ['Lecture and lab units cannot both be zero.']
                ]
            ], 422);
        }

        if ($validated['subject_category'] == "MINOR") {
            $validated['year_level'] = null;
            $validated['semester'] = null;
        }

        $subject->update($validated);

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $decrypted = Crypt::decryptString($id);
        $subject = Subject::findOrFail($decrypted);
        
        $subject->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Subject deleted successfully.'
        ]);
    }

    public function toggle($id)
    {
        try {
            $decrypted = Crypt::decryptString($id);
            
            $subject = Subject::findOrFail($decrypted);
            $subject->is_active = !$subject->is_active;
            $subject->update();
            
            return response()->json([
                'success' => true,
                'message' => 'Subject status toggled successfully.'
            ]);
        } catch (DecryptException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid subject ID. Could not toggle status.'
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }
}
