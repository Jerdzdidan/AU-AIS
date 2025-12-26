<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GradeImport extends Model
{
    //

    protected $fillable = [
        'user_id',
        'filename',
        'total_rows',
        'valid_rows',
        'invalid_rows',
        'status',
        'notes',
        'processed_at',
    ];

    public function rows(){
        return $this->hasMany(GradeImportRow::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function grades(){
        return $this->hasMany(Grade::class);
    }
}
