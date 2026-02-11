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
        'laboratory_status',
        'lecture_grade',
        'laboratory_grade',
        'grade',
        'remarks',
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
            return $this->lecture_status === "COMPLETED" && $this->laboratory_status === "COMPLETED";
        }

        if ($hasLec) {
            return $this->lecture_status === "COMPLETED";
        }

        if ($hasLab) {
            return $this->laboratory_status === "COMPLETED";
        }

        // Edge case: both units are 0 (shouldn't happen per your store validation, but just in case)
        return false;
    }

    public function hasLec()
    {
        return $this->subject->lec_units > 0;
    }

    public function hasLab()
    {
        return $this->subject->lab_units > 0;
    }
}
