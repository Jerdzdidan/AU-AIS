<?php

namespace App\Http\Controllers\AdminPanel;

use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    //
    public function index(UserType $user_type)
    {
        $users = User::where('user_type', $user_type->value)->get();

        $viewPath = match($user_type) {
            UserType::ADMIN => 'app.admin_panel.user_management.admin_accounts.index',
            UserType::OFFICER => 'app.admin_panel.user_management.officer_accounts.index',
            UserType::STUDENT => 'app.admin_panel.user_management.student_accounts.index',
        };
        
        return view($viewPath, compact('users', 'user_type'));
    }

    public function getData($user_type)
    {
        $users = User::where('user_type', $user_type)->select(['id', 'name', 'email', 'user_type', 'status']);
        
        return DataTables::of($users)
            ->editColumn('id', function ($row) {
                return Crypt::encryptString($row->id);
            })
            ->make(true);
    }

    public function getStats($user_type)
    {
        return response()->json([
            'total' => User::where('user_type', $user_type)->count(),
            'active' => User::where('user_type', $user_type)->where('status', true)->count(),
            'inactive' => User::where('user_type', $user_type)->where('status', false)->count(),
        ]);
    }

    public function store(Request $request, $user_type)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        $user = new User();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->password = Hash::make($validated['password']);
        $user->user_type = strtoupper($user_type);

        $user->save();
    }

    public function edit($id) 
    {
        $decrypted = Crypt::decryptString($id);

        $user = User::findOrFail($decrypted);

        return response()->json([
            'id'        => Crypt::encryptString($user->id),
            'name'      => $user->name,
            'email'     => $user->email,
            'user_type' => $user->user_type,
        ]);
    }

    public function update(Request $request, $id)
    {
        $decrypted = Crypt::decryptString($id);

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'email' => [
            'required',
            'email',
            Rule::unique('users', 'email')->ignore($decrypted),
        ],
            'password' => 'nullable|string|min:6',
        ]);

        $user = User::findOrFail($decrypted);
        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();
    }

    public function destroy($id)
    {
        try {
            $decrypted = Crypt::decryptString($id);
            
            $user = User::findOrFail($decrypted);
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully.'
            ]);
        } catch (DecryptException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid user ID. Could not delete.'
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }
}
