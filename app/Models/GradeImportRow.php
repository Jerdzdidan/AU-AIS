<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GradeImportRow extends Model
{
    //
    protected $fillable = [
        'grade_import_id',
        'student_number',
        'subject_code',
        'subject_name',
        'unit_type',
        'school_year',
        'semester',
        'faculty',
        'credit_unit',
        'grade',
        'validity',
        'status',
        'errors',
    ];

    protected $casts = [
        'errors' => 'array',
        'credit_unit' => 'decimal:2',
    ];

    public function gradeImport(){
        return $this->belongsTo(GradeImport::class);
    }
    
    public function grade(){
        return $this->hasOne(Grade::class);
    }

    public function getErrorMessages()
    {
        return $this->errors ?? [];
    }
}
