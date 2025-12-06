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
        'lecture_status',
        'lab_status',
        'lecture_grade',
        'lab_grade',
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
        $lecCompleted = $this->lecture_completed;
        $labCompleted = $this->lab_completed;
        
        return $lecCompleted && $labCompleted;
    }
}
