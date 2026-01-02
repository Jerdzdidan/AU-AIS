<?php

namespace App\Http\Controllers\StudentPortal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GradeController extends Controller
{
    //
    public function index() {
        $student = Auth::user()->student;

        $grades = $student->grades()->get();

        return view('app.student_portal.grades.index', [
            'student' => $student,
            'grades' => $grades,
        ]);
    }
}
