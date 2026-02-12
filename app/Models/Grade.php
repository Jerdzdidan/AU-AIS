<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    //
    protected $fillable = [
        'student_id',
        'subject_code',
        'subject_name',
        'unit_type',
        'school_year',
        'semester',
        'faculty',
        'credit_unit',
        'grade',
        'grade_import_id',
        'grade_import_row_id',
    ];

    public function is_passed()
    {
        return $this->grade >= 1.00 && $this->grade <= 3.00;
    }

    public function student(){
        return $this->belongsTo(Student::class);
    }

    public function gradeImport(){
        return $this->belongsTo(GradeImport::class);
    }

    public function gradeImportRow(){
        return $this->belongsTo(GradeImportRow::class);
    }
}
