<?php

namespace App\Listeners;

use App\Models\StudentSubjectProgress;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class UpdateAcademicProgress
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
    public function handle(object $event): void
    {
        $student = $event->student;

        StudentSubjectProgress::where('student_id', $student->id)->update([
            'lecture_completed' => false,
            'laboratory_completed' => false,
        ]);

        $subjects = $student->studentSubjectProgress;
        $grades = $student->grades()->get();

        foreach ($grades as $grade) {
            $subjectProgress = $subjects->first(function ($progress) use ($grade) {
                return $progress->subject->code === $grade->subject_code;
            });

            if (!$subjectProgress) {
                continue;
            }

            if ($grade->unit_type == 'lec' && $grade->is_passed())
            {
                $subjectProgress->lecture_completed = true;
            }
            if ($grade->unit_type == 'lab' && $grade->is_passed())
            {
                $subjectProgress->laboratory_completed = true;
            }

            $subjectProgress->save();
        }
    }
}
