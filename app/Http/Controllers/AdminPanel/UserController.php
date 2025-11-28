<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    //
    public function getData($user_type)
    {
        $users = User::where('user_type', $user_type)->where('name', '!=', 'root')->select(['id', 'name', 'email', 'user_type', 'status']);
        
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

    public function toggle($id)
    {
        try {
            $decrypted = Crypt::decryptString($id);
            
            $user = User::findOrFail($decrypted);
            $user->status = !$user->status;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'User status toggled successfully.'
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
