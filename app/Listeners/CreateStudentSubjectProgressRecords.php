<?php

namespace App\Listeners;

use App\Events\StudentAcademicProgressOpen;
use App\Models\StudentSubjectProgress;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class CreateStudentSubjectProgressRecords
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(StudentAcademicProgressOpen $event): void
    {
        $student = $event->student;

        $subjects = $student->program->curriculum->subjects;
        $subjectIds = $subjects->pluck('id')->all();

        DB::transaction(function () use ($student, $subjects, $subjectIds) {
            StudentSubjectProgress::where('student_id', $student->id)
                ->whereNotIn('subject_id', $subjectIds)
                ->delete();
            
            foreach ($subjects as $subject) {
                $progress = StudentSubjectProgress::where('student_id', $student->id)
                    ->where('subject_id', $subject->id)
                    ->first();

                if (!$progress) {
                    StudentSubjectProgress::create([
                        'student_id' => $student->id,
                        'subject_id' => $subject->id,
                        'lecture_completed' => false,
                        'lab_completed' => false
                    ]);
                    continue;
                }
            }
        });
    }
}
