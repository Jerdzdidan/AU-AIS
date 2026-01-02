<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicPeriod extends Model
{
    use HasFactory;
    //
    protected $fillable = [
        'name',
        'school_year',
        'year_start',
        'year_end',
        'semester',
        'is_current',
    ];

    public function grade_imports()
    {
        return $this->hasMany(GradeImport::class);
    }
}
