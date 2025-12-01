<?php

namespace App\Models;

use App\Traits\ChecksAssociations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curriculum extends Model
{
    use ChecksAssociations;
    use HasFactory;
    //
    protected $fillable = ['program_id', 'description', 'is_active'];

    protected function getRelationshipsToCheck()
    {
        return [
            'subjects' => 'subjects',
        ];
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }
}
