<?php

namespace App\Models;

use App\Traits\ChecksAssociations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicPeriod extends Model
{
    use ChecksAssociations;
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

    protected function getRelationshipsToCheck()
    {
        return [
            'grade_imports' => 'grade import/s',
        ];
    }

    public function grade_imports()
    {
        return $this->hasMany(GradeImport::class);
    }
}
