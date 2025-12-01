<?php

namespace App\Listeners;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Hash;

class StudentCreationListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        $student = $event->student;
        //
        $user = User::create([
            'name' => $event->name,
            'email' => null,
            'password' => Hash::make($event->password),
            'user_type' => 'STUDENT',
            'status' => true,
        ]);
        
        $student->user_id = $user->id;
        $student->save();
    }
}
