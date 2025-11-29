<?php

namespace App\Models;

use App\Traits\ChecksAssociations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use ChecksAssociations;
    use HasFactory;
    //

    protected $fillable = ['name', 'code', 'head_of_department'];

    protected function getRelationshipsToCheck()
    {
        return [
            'users' => 'users',
        ];
    }
    
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function programs()
    {
        return $this->hasMany(Program::class);
    }
}
