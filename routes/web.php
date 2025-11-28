<?php

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
Route::prefix('admin')->namespace()->group(function () {
    Route::prefix('users')->group(function () {
        Route::get('{user_type}', [UserController::class, 'index'])->name('users.index');
        Route::get('data/{user_type}', [UserController::class, 'getData'])->name('users.data');
        Route::get('stats/{user_type}', [UserController::class, 'getStats'])->name('users.stats');

        Route::post('store/{user_type}', [UserController::class, 'store'])->name('users.store');
        Route::get('edit/{id}', [UserController::class, 'edit'])->where('id', '.*')->middleware(PreventSelfAction::class)->name('users.edit');
        Route::put('update/{id}', [UserController::class, 'update'])->where('id', '.*')->middleware(PreventSelfAction::class)->name('users.update');
        Route::delete('destroy/{id}', [UserController::class, 'destroy'])->middleware(PreventSelfAction::class)->name('users.destroy');
        Route::post('toggle-status/{id}', [UserController::class, 'toggle'])->middleware(PreventSelfAction::class)->name('users.toggle');
    });
});

