<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;
    //

    protected $fillable = ['name', 'code', 'head_of_department'];
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
