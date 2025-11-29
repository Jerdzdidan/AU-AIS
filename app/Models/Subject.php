<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;
    //

    protected $fillable = [
        'curriculum_id',
        'code',
        'name',
        'year_level',
        'semester',
        'subject_category',
        'lec_units',
        'lab_units',
        'prerequisites',
        'is_active',
    ];

    public function curriculum()
    {
        return $this->belongsTo(Curriculum::class);
    }
}
