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

    public function getData(Request $request)
    {
        $curriculum = Curriculum::with('program:id,name,code')->select(['id', 'program_id', 'description', 'year_start', 'year_end', 'is_active']);
        
        if ($request->filled('status') && $request->status !== 'All') {
            if ($request->status === 'Active') {
                $curriculum->where('is_active', true);
            } elseif ($request->status === 'Inactive') {
                $curriculum->where('is_active', false);
            }
        }

        return DataTables::of($curriculum)
            ->editColumn('id', function ($row) {
                return Crypt::encryptString($row->id);
            })
            ->addColumn('name', function ($row) {
                return $row->program->code . ' - Curriculum (' . $row->year_start . ' - ' . $row->year_end . ')';
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
            'program_id' => 'required|exists:programs,id',
            'description' => 'nullable|string',
            'year_start' => 'required|string|max:4',
            'year_end' => 'required|string|max:4',
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
            'year_start' => $curriculum->year_start,
            'year_end' => $curriculum->year_end,
            'description' => $curriculum->description,
        ]);
    }

    public function update(Request $request, $id)
    {
        $decrypted = Crypt::decryptString($id);
        $curriculum = Curriculum::findOrFail($decrypted);

        $validated = $request->validate([
            'program_id' => 'required|exists:programs,id',
            'description' => 'nullable|string',
            'year_start' => 'required|string|max:4',
            'year_end' => 'required|string|max:4',
        ]);

        $curriculum->update($validated);

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $decrypted = Crypt::decryptString($id);
        $curriculum = Curriculum::findOrFail($decrypted);

        // Check associations
        $check = $curriculum->checkAssociations();
        
        if ($check['hasAssociations']) {
            return response()->json([
                'success' => false,
                'message' => $check['message']
            ], 422);
        }
        
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

    public function getCurriculaForSelect(Request $request, $program_id)
    {
        $query = Curriculum::query();

        if ($search = $request->input('q')) {
            $query->where('year_start', 'like', "%{$search}%")
                ->orWhere('year_end', 'like', "%{$search}%");
        }

        $curricula = $query->with('program:id,name')
                           ->where('program_id', $program_id)
                           ->where('is_active', true)
                           ->get(['id', 'program_id', 'year_start', 'year_end']);
        
        $formatted = $curricula->map(function ($curriculum) {
            return [
                'id' => $curriculum->id,
                'name' => $curriculum->program->name . ' - Curriculum (' . $curriculum->year_start . '-' . $curriculum->year_end . ')'
            ];
        });

        return response()->json($formatted);
    }

}