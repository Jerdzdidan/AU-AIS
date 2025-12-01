<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StudentAuthController extends Controller
{
    //
    // public function index() {
    //     return view('auth.admin.login');
    // }

    public function authenticate(Request $request): RedirectResponse
    {
        $request->validate([
            'student_number' => ['required'],
            'password' => ['required'],
        ]);

        $student = Student::where('student_number', $request->student_number)->first();

        if (!$student) {
            return back()->withErrors([
                'student_number' => 'Student number not found.',
            ]);
        }

        $user = $student->user;

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'password' => 'Incorrect password.',
            ]);
        }

        if (!$user->status) {
            return back()->with('error', 'Your account is inactive.');
        }

        Auth::login($user);

        $request->session()->regenerate();

        return redirect()->route('home');
    }
}
