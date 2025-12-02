<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    //

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function studentSubjectProgress()
    {
        return $this->hasMany(StudentSubjectProgress::class);
    }

    public function getProgressPercentage()
    {
        $allSubjects = $this->program->curriculum->subjects ?? collect();
        $totalSubjects = $allSubjects->count();
        
        if ($totalSubjects === 0) return 0;
        
        $completedCount = $this->subjectProgress()
            ->where('lecture_status', 'completed')
            ->where(function($q) {
                $q->where('lab_status', 'completed')
                  ->orWhere('lab_status', 'not_applicable');
            })
            ->count();
        
        return round(($completedCount / $totalSubjects) * 100, 2);
    }
}
