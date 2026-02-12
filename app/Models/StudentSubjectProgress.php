<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentSubjectProgress extends Model
{
    //
    protected $table = 'student_subject_progress';

    protected $fillable = [
        'student_id',
        'subject_id',
        'lecture_completed',
        'laboratory_completed',
        'lecture_grade',
        'laboratory_grade',
        'semester_taken',
        'year_taken',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function isCompleted()
    {
        $hasLec = $this->subject->lec_units > 0;
        $hasLab = $this->subject->lab_units > 0;

        if ($hasLec && $hasLab) {
            return $this->lecture_completed && $this->laboratory_completed;
        }

        if ($hasLec) {
            return $this->lecture_completed;
        }

        if ($hasLab) {
            return $this->laboratory_completed;
        }

        // Edge case: both units are 0 (shouldn't happen per your store validation, but just in case)
        return false;
    }
}
