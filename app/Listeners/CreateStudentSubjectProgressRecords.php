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
    public function handle(object $event): void
    {
        $student = $event->student;

        $subjects = $student->curriculum->subjects;
        $currentSubjectIds = $subjects->pluck('id')->toArray();

        DB::transaction(function () use ($student, $subjects, $currentSubjectIds) {
            StudentSubjectProgress::where('student_id', $student->id)
                ->whereNotIn('subject_id', $currentSubjectIds)
                ->delete();
            
            foreach ($subjects as $subject) {
                if($subject->is_active){
                    StudentSubjectProgress::firstOrCreate(
                        [
                            'student_id' => $student->id,
                            'subject_id' => $subject->id,
                        ],
                        [
                            'lecture_completed' => false,
                            'laboratory_completed' => false,
                            'lecture_grade' => null,
                            'laboratory_grade' => null,
                            'semester_taken' => null,
                            'year_taken' => null,
                        ]
                    );
                }
            }
        });
    }
}
