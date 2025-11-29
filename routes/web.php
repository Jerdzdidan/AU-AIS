<?php

use App\Http\Controllers\AdminPanel\AdminUserController;
use App\Http\Controllers\AdminPanel\DepartmentController;
use App\Http\Controllers\AdminPanel\OfficerUserController;
use App\Http\Controllers\AdminPanel\UserController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\CreateRootUserController;
use App\Http\Middleware\PreventSelfAction;
use Illuminate\Support\Facades\Route;

// PLACEHOLDER ROUTE
Route::get('/placeholder', function () {
    return 'This page is under construction.';
})->name('#');

// HOME PAGE
Route::get('/', function () {
    return view('home');
})->middleware('auth')->name('home');

// ADMIN LOGIN/AUTHENTICATION
Route::prefix('auth/admin')->group(function () {
    Route::get('login', [AdminAuthController::class, 'index'])->name('auth.admin.login');
    Route::post('authenticate', [AdminAuthController::class, 'authenticate'])->name('auth.admin.authenticate');
    Route::get('logout', [AdminAuthController::class, 'logout'])->name('auth.admin.logout');
});

// STUDENT LOGIN/AUTHENTICATION
Route::prefix('auth/student')->group(function () {
    // Route::get('login', [AdminAuthController::class, 'index'])->name('auth.login');
    // Route::post('authenticate', [AdminAuthController::class, 'authenticate'])->name('auth.authenticate');
});


// ADMIN PANEL
Route::prefix('admin')->middleware('auth')->group(function () {

    // USER MANAGEMENT
    Route::prefix('users')->group(function () {
        Route::resource('admins', AdminUserController::class);
        Route::resource('officers', OfficerUserController::class);

        Route::get('data/{user_type}', [UserController::class, 'getData'])->name('users.data');
        Route::get('stats/{user_type}', [UserController::class, 'getStats'])->name('users.stats');
        Route::post('toggle-status/{id}', [UserController::class, 'toggle'])->middleware(PreventSelfAction::class)->name('users.toggle');
    });

    // DEPARTMENTS MANAGEMENT
    Route::prefix('departments')->group(function () {
        Route::resource('/', DepartmentController::class)
            ->names([
                'index' => 'departments.index', 'create' => 'departments.create', 'store' => 'departments.store', 
                'show' => 'departments.show', 'edit' => 'departments.edit', 'update' => 'departments.update',
                'destroy' => 'departments.destroy',
            ]);

        Route::get('data', [DepartmentController::class, 'getData'])->name('departments.data');
        Route::get('stats', [DepartmentController::class, 'getStats'])->name('departments.stats');

        // FOR SELECT2
        Route::get('select', [DepartmentController::class, 'getDepartmentsForSelect'])->name('departments.select');
    });


});

