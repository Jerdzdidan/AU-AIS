<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class DepartmentController extends Controller
{
    //
    public function index()
    {
        return view('app.admin_panel.department_management.index');
    }

    public function getData()
    {
        $departments = Department::select(['id', 'name', 'code', 'head_of_department']);
        
        return DataTables::of($departments)
            ->editColumn('id', function ($row) {
                return Crypt::encryptString($row->id);
            })
            ->make(true);
    }

    public function getStats()
    {
        return response()->json([
            'total' => Department::count()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255|unique:departments,name',
            'code'    => 'required|string|max:255|unique:departments,code',
            'head_of_department' => 'required|string|max:255|unique:departments,head_of_department',
        ]);

        Department::create($validated);
        return response()->json(['success' => true]);
    }

    public function edit($id) 
    {
        $decrypted = Crypt::decryptString($id);

        $department = Department::findOrFail($decrypted);

        return response()->json([
            'id' => Crypt::encryptString($department->id),
            'name' => $department->name,
            'code' => $department->code,
            'head_of_department' => $department->head_of_department,
        ]);
    }

    public function update(Request $request, $id)
    {
        $decrypted = Crypt::decryptString($id);
        $department = Department::findOrFail($decrypted);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255',
                Rule::unique('departments', 'name')->ignore($decrypted),
            ],
            'code' => ['required', 'string', 'max:255',
                Rule::unique('departments', 'code')->ignore($decrypted),
            ],
            'head_of_department' => ['required', 'string', 'max:255',
                Rule::unique('departments', 'head_of_department')->ignore($decrypted),
            ]
        ]);

        $department->update($validated);

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $decrypted = Crypt::decryptString($id);
        $department = Department::findOrFail($decrypted);
        
        // Check associations
        $check = $department->checkAssociations();
        
        if ($check['hasAssociations']) {
            return response()->json([
                'success' => false,
                'message' => $check['message']
            ], 422);
        }
        
        $department->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Department deleted successfully.'
        ]);
    }

    public function getDepartmentsForSelect(Request $request)
    {
        $query = Department::query();

        if ($search = $request->input('q')) {
            $query->where('name', 'like', "%{$search}%");
        }

        $departments = $query->get(['id', 'name', 'code']);

        $formatted = $departments->map(function ($dept) {
            return [
                'id' => $dept->id,
                'name' => $dept->name,
                'code' => $dept->code
            ];
        });

        return response()->json($formatted);
    }

}
