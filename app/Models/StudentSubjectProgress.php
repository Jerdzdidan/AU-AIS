<?php

namespace App\Models;

use App\Enums\GradeStatus;
use Illuminate\Database\Eloquent\Model;
use App\Enums\SubjectCategory;

class StudentSubjectProgress extends Model
{
    //
    protected $table = 'student_subject_progress';

    protected $fillable = [
        'student_id',
        'subject_id',
        'lecture_status',
        'laboratory_status',
        'final_grade',
        'remarks',
        'lecture_grade',
        'laboratory_grade',
        'semester_taken',
        'year_taken',
    ];

    protected $casts = [
        'lecture_status' => GradeStatus::class,
        'laboratory_status' => GradeStatus::class,
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
        if ($this->remarks == "completed" || $this->remarks == "failed") {
            return true;
        }
    }

    public function has_lec()
    {
        return $this->subject->lec_units > 0;
    }

    public function has_lab()
    {
        return $this->subject->lab_units > 0;
    }
}
