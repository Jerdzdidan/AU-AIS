<?php

namespace App\Models;

use App\Traits\ChecksAssociations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use ChecksAssociations;
    use HasFactory;
    //
    protected $fillable = ['name', 'code', 'description', 'department_id'];

    protected function getRelationshipsToCheck()
    {
        return [
            'curriculum' => 'curriculum',
        ];
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function curriculum()
    {
        return $this->hasOne(Curriculum::class);
    }
}
