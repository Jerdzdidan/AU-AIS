<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class ProgramController extends Controller
{
    //
        public function index()
    {
        return view('app.admin_panel.program_management.index');
    }

    public function getData()
    {
        $programs = Program::with('department:id,name')->select(['id', 'name', 'code', 'department_id', 'description']);
        
        return DataTables::of($programs)
            ->editColumn('id', function ($row) {
                return Crypt::encryptString($row->id);
            })
            ->make(true);
    }

    public function getStats()
    {
        return response()->json([
            'total' => Program::count()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255|unique:programs,name',
            'code'    => 'required|string|max:255|unique:programs,code',
            'description' => 'nullable|string',
            'department_id' => 'required|exists:departments,id',
        ]);

        Program::create($validated);
        return response()->json(['success' => true]);
    }

    public function edit($id) 
    {
        $decrypted = Crypt::decryptString($id);

        $program = Program::findOrFail($decrypted);

        return response()->json([
            'id' => Crypt::encryptString($program->id),
            'name' => $program->name,
            'code' => $program->code,
            'description' => $program->description,
            'department_id' => $program->department_id,
        ]);
    }

    public function update(Request $request, $id)
    {
        $decrypted = Crypt::decryptString($id);
        $program = Program::findOrFail($decrypted);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255',
                Rule::unique('programs', 'name')->ignore($decrypted),
            ],
            'code' => ['required', 'string', 'max:255',
                Rule::unique('programs', 'code')->ignore($decrypted),
            ],
            'description' => 'nullable|string',
            'department_id' => 'required|exists:departments,id',
        ]);

        $program->update($validated);

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $decrypted = Crypt::decryptString($id);
        $program = Program::findOrFail($decrypted);
        
        $program->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Program deleted successfully.'
        ]);
    }

    public function getProgramsForSelect(Request $request)
    {
        $query = Program::query();

        if ($search = $request->input('q')) {
            $query->where('name', 'like', "%{$search}%");
        }

        $programs = $query->get(['id', 'name', 'code']);
        
        $formatted = $programs->map(function ($program) {
            return [
                'id' => $program->id,
                'name' => $program->name,
                'code' => $program->code
            ];
        });

        return response()->json($formatted);
    }
}
