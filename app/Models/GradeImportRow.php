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
        'subject_name',
        'unit_type',
        'school_year',
        'semester',
        'faculty',
        'credit_unit',
        'grade',
        'status',
        'errors',
    ];

    protected $casts = [
        'errors' => 'array',
        'grade' => 'decimal:2',
        'credit_unit' => 'decimal:2',
    ];

    public function gradeImport(){
        return $this->belongsTo(GradeImport::class);
    }
}
