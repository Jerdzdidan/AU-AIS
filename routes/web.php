<?php

use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\CreateRootUserController;
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

Route::prefix('admin')->namespace()->group(function () {
    
});

// STUDENT LOGIN/AUTHENTICATION
Route::prefix('auth/student')->group(function () {
    // Route::get('login', [AdminAuthController::class, 'index'])->name('auth.login');
    // Route::post('authenticate', [AdminAuthController::class, 'authenticate'])->name('auth.authenticate');
});






// CREATION OF ROOT ACCOUNT
Route::get('create-root', [CreateRootUserController::class, 'createRootUser']);