<?php

namespace App\Http\Controllers\AdminPanel;

use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //

    public function index(UserType $user_type)
    {
        $users = User::where('user_type', $user_type->value)->get();

        $viewPath = match($user_type) {
            UserType::ADMIN => 'app.admin_panel.user_management.admin_accounts.index',
            UserType::OFFICER => 'app.admin_panel.user_management.officer_accounts.index',
            UserType::STUDENT => 'app.admin_panel.user_management.student_accounts.index',
        };
        
        return view($viewPath, compact('users', 'user_type'));
    }
}
