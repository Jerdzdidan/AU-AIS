<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AccountSettingsController extends Controller
{
    /**
     * Update the authenticated user's email address (students only).
     */
    public function updateEmail(Request $request)
    {
        if (auth()->user()->user_type !== 'STUDENT') {
            abort(403, 'Only students can update their email address.');
        }

        $request->validate([
            'email' => ['required', 'email', 'unique:users,email,' . auth()->id()],
        ]);

        $user = auth()->user();
        $user->email = $request->email;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Email address updated successfully.',
        ]);
    }

    /**
     * Update the authenticated user's password (all roles).
     */
    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => ['required'],
            'new_password'     => ['required', 'min:6', 'confirmed'],
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();

            // Mirror new_password error to new_password_confirmation
            if (isset($errors['new_password'])) {
                $errors['new_password_confirmation'] = $errors['new_password'];
            }

            return response()->json(['errors' => $errors], 422);
        }

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'errors'  => [
                    'current_password' => ['The current password is incorrect.'],
                ],
            ], 422);
        }

        $user->password = $request->new_password;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully.',
        ]);
    }
}
