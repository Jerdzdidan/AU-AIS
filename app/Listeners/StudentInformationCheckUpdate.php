<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class StudentInformationCheckUpdate
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
        //
        $student = $event->student;

        $year = now()->year;      // 2026
        $lastTwo = (int) substr($year, -2);

        $student_number = $student->student_number;

        $firstTwo = (int) substr($student_number, 0, 2);

        if ($lastTwo - $firstTwo > 5) {
            $student->year = 5;
        }
        else {
            $student->year = $lastTwo - $firstTwo;
        }
    }
}
