<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GradeImportRow extends Model
{
    //
    protected $fillable = [
        'grade_import_id',
        'raw_student_identifier',
        'student_id',
        'subject_code',
        'school_year',
        'semester',
        'faculty',
        'credit_unit',
        'grade',
        'status',
        'errors',
    ];

    public function gradeImport(){
        return $this->belongsTo(GradeImport::class);
    }

    public function isValid(){
        return empty($this->errors);
    }
}
