<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GradeImport extends Model
{
    //

    protected $fillable = [
        'user_id',
        'filename',
        'academic_period_id',
        'total_rows',
        'valid_rows',
        'invalid_rows',
        'status',
        'notes',
        'processed_at',
    ];

    public function academic_period()
    {
        return $this->belongsTo(AcademicPeriod::class);
    }

    public function rows(){
        return $this->hasMany(GradeImportRow::class);
    }

    public function grades(){
        return $this->hasMany(Grade::class);
    }
}
