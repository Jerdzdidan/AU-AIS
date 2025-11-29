<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Models\Curriculum;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\DataTables;

class CurriculumController extends Controller
{
    //
    public function index()
    {
        return view('app.admin_panel.curriculum_management.index');
    }

    public function getData()
    {
        $curriculum = Curriculum::with('program:id,name,code')->select(['id', 'program_id', 'description', 'is_active']);
        
        return DataTables::of($curriculum)
            ->editColumn('id', function ($row) {
                return Crypt::encryptString($row->id);
            })
            ->addColumn('name', function ($row) {
                return $row->program->code . ' - Curriculum';
            })
            ->make(true);
    }

    public function getStats()
    {
        return response()->json([
            'total' => Curriculum::count(),
            'active' => Curriculum::where('is_active', true)->count(),
            'inactive' => Curriculum::where('is_active', false)->count(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'program_id' => 'required|exists:programs,id|unique:curricula,program_id',
            'description' => 'nullable|string',
        ], 
        [
            'program_id.required' => 'The program field is required.',
            'program_id.exists' => 'The selected program does not exist.',
            'program_id.unique' => 'The selected program already has a curriculum.'
        ]);

        Curriculum::create($validated);
        return response()->json(['success' => true]);
    }

    public function edit($id) 
    {
        $decrypted = Crypt::decryptString($id);

        $curriculum = Curriculum::findOrFail($decrypted);

        return response()->json([
            'id' => Crypt::encryptString($curriculum->id),
            'program_id' => $curriculum->program_id,
            'description' => $curriculum->description,
        ]);
    }

    public function update(Request $request, $id)
    {
        $decrypted = Crypt::decryptString($id);
        $curriculum = Curriculum::findOrFail($decrypted);

        $validated = $request->validate([
            'program_id' => 'required|exists:programs,id|unique:curricula,program_id,' . $curriculum->id,
            'description' => 'nullable|string',
        ], 
        [
            'program_id.required' => 'The program field is required.',
            'program_id.exists' => 'The selected program does not exist.',
            'program_id.unique' => 'The selected program already has a curriculum.'
        ]);

        $curriculum->update($validated);

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $decrypted = Crypt::decryptString($id);
        $curriculum = Curriculum::findOrFail($decrypted);
        
        $curriculum->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Curriculum deleted successfully.'
        ]);
    }

    public function toggle($id)
    {
        try {
            $decrypted = Crypt::decryptString($id);
            
            $curriculum = Curriculum::findOrFail($decrypted);
            $curriculum->is_active = !$curriculum->is_active;
            $curriculum->update();

            return response()->json([
                'success' => true,
                'message' => 'Curriculum status toggled successfully.'
            ]);
        } catch (DecryptException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid curriculum ID. Could not toggle status.'
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }

}