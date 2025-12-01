<?php

namespace App\Http\Controllers\AdminPanel;

use App\Events\StudentCreationEvent;
use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    //
    public function index()
    {
        return view('app.admin_panel.user_management.student_accounts.index');
    }

    public function store(Request $request)
    {
        $student_info_validated = $request->validate([
            'student_number' => 'required|string|unique:students,student_number',
            'program_id' => 'required|exists:programs,id',
            'year_level' => 'required|string',
        ]);

        $user_info_validated = $request->validate([
            'name'     => 'required|string|max:255',
            'password' => 'required|string|min:6',
        ]);

        $student = new Student();
        $student->student_number = $student_info_validated['student_number'];
        $student->program_id = $student_info_validated['program_id'];
        $student->year_level = $student_info_validated['year_level'];

        $student->save();

        event(new StudentCreationEvent($student, $user_info_validated));
    }

    public function edit($id) 
    {
        $decrypted = Crypt::decryptString($id);

        $student_profile = Student::findOrFail($decrypted);

        return response()->json([
            'id' => Crypt::encryptString($student_profile->id),
            'name' => $student_profile->user->name ?? null,
            'email' => $student_profile->user->email ?? null,
            'department_id' => $student_profile->department_id,
            'department_name' => $student_profile->department->name ?? null,
        ]);
    }

    public function update(Request $request, $id)
    {
        $decrypted = Crypt::decryptString($id);

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'password' => 'required|string|min:6',
            'student_number' => 'required|string|unique:students,student_number,' . $decrypted,
            'password' => 'nullable|string|min:6',
            'year_level' => 'required|string',
        ]);

        $student = Student::findOrFail($decrypted);
        $student->student_number = $validated['student_number'];
        $student->program_id = $validated['program_id'];
        $student->year_level = $validated['year_level'];

        $student->user->name = $validated['name'];
        if (!empty($validated['password'])) {
            $student->user->password = Hash::make($validated['password']);
        }

        $student->save();
        $student->user->save();
    }

    public function destroy($id)
    {
        $decrypted = Crypt::decryptString($id);
        $student = Student::findOrFail($decrypted);

        DB::transaction(function() use ($student) {
            $student->user->delete();
            $student->delete();
        });

        return response()->json([
            'success' => true,
            'message' => 'Officer deleted successfully.'
        ]);
    }
}
