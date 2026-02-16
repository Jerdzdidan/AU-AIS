<?php

namespace App\Http\Controllers\OfficerPanel;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\Facades\DataTables;

class StudentAcademicProgressController extends Controller
{
    //
    public function index($year)
    {
        return view('app.officer_panel.student_academic_progress', compact('year'));
    }

    public function getData(Request $request, $year)
    {
        $programs = $request->department->programs;

        $students = Student::with(['user:id,name,email,status', 'program:id,name,code', 'curriculum:id,year_start,year_end'])
            ->where('year_level', $year)
            ->where('status', 'Active')
            ->where('program', $programs)
            ->select(['id', 'user_id', 'student_number', 'program_id', 'curriculum_id', 'year_level']);

        return DataTables::of($students)
            ->addColumn('curriculum', function ($row) {
                return $row->program->code . ' - Curriculum (' . $row->curriculum->year_start . '-' . $row->curriculum->year_end . ')';
            })
            ->editColumn('id', function ($row) {
                return Crypt::encryptString($row->id);
            })
            ->editColumn('user_id', function ($row) {
                return Crypt::encryptString($row->user_id);
            })
            ->make(true);
    }
}
