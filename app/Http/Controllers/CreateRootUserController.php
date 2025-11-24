<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class CreateRootUserController extends Controller
{
    //

    public function createRootUser() {
        $user = new User();

        $user->name = 'root';
        $user->email = 'root@gmail.com';
        $user->password = '123456';
        $user->save();

        return redirect()->route('home');
    }
}
