<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class OfficerUserController extends Controller
{
    //
    public function index()
    {
        return view('app.admin_panel.user_management.officer_accounts.index');
    }

    public function store(Request $request)
    {
        try {
            $departmentId = Crypt::decryptString($request->department_id);
        } catch (\Exception $e) {
            return back()->withErrors(['department_id' => 'Invalid department value.']);
        }

        $request->merge(['department_id' => $departmentId]);

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'department_id'=> 'required|exists:departments,id'
        ]);

        $user = new User();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->password = Hash::make($validated['password']);
        $user->user_type = 'OFFICER';
        $user->department_id = $departmentId;

        $user->save();
    }

    public function edit($id) 
    {
        $decrypted = Crypt::decryptString($id);

        $user = User::findOrFail($decrypted);

        return response()->json([
            'id' => Crypt::encryptString($user->id),
            'name' => $user->name,
            'email' => $user->email,
            'department' => $user->department
        ]);
    }

    public function update(Request $request, $id)
    {
        $decrypted = Crypt::decryptString($id);

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($decrypted),
            ],
            'password' => 'nullable|string|min:6',
            'department' => 'required',
        ]);

        $user = User::findOrFail($decrypted);
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->department = $validated['department'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();
    }

    public function destroy($id)
    {
        $decrypted = Crypt::decryptString($id);
        User::findOrFail($decrypted)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Officer deleted successfully.'
        ]);
    }
}
