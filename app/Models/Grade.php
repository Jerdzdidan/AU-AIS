<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    //
    protected $fillable = [
        'student_id',
        'subject_id',
        'school_year',
        'semester',
        'faculty',
        'credit_unit',
        'grade',
        'grade_import_id',
    ];

    public function student(){
        return $this->belongsTo(Student::class);
    }

    public function gradeImport(){
        return $this->belongsTo(GradeImport::class);
    }
}
