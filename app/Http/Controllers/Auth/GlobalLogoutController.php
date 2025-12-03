<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GlobalLogoutController extends Controller
{
    //
    public function logout(Request $request, $user_type): RedirectResponse
    {
        Auth::logout();
    
        $request->session()->invalidate();
    
        $request->session()->regenerateToken();

        if ($user_type === 'STUDENT') {
            return redirect()->route('auth.student.login');
        }
        else if ($user_type === 'ADMIN' || $user_type === 'OFFICER') {
            return redirect()->route('auth.admin.login');
        }

        return redirect()->route('auth.index');
    }
}
