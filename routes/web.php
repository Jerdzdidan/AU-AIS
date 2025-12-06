<?php

use App\Http\Controllers\AdminPanel\AdminUserController;
use App\Http\Controllers\AdminPanel\CurriculumController;
use App\Http\Controllers\AdminPanel\DepartmentController;
use App\Http\Controllers\AdminPanel\OfficerUserController;
use App\Http\Controllers\AdminPanel\ProgramController;
use App\Http\Controllers\AdminPanel\StudentUserController;
use App\Http\Controllers\AdminPanel\SubjectController;
use App\Http\Controllers\AdminPanel\UserController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\GlobalLogoutController;
use App\Http\Controllers\Auth\StudentAuthController;
use App\Http\Controllers\StudentPortal\AcademicProgress;
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

// DEFAULT ROUTE FOR AUTHENTICATION SELECTION
Route::view('auth', 'auth.index')->name('auth.index');

// ADMIN LOGIN/AUTHENTICATION
Route::prefix('auth/admin')->group(function () {
    Route::get('login', [AdminAuthController::class, 'index'])->name('auth.admin.login');
    Route::post('authenticate', [AdminAuthController::class, 'authenticate'])->name('auth.admin.authenticate');
});

// STUDENT LOGIN/AUTHENTICATION
Route::prefix('auth/student')->group(function () {
    Route::get('login', [StudentAuthController::class, 'index'])->name('auth.student.login');
    Route::post('authenticate', [StudentAuthController::class, 'authenticate'])->name('auth.student.authenticate');
});

// LOGOUT
Route::get('auth/logout/{user_type}', [GlobalLogoutController::class, 'logout'])->name('auth.logout');


// ADMIN PANEL
Route::prefix('admin')->middleware('auth')->group(function () {

    // USER MANAGEMENT
    Route::prefix('users')->group(function () {
        // ADMIN ACCOUNTS MANAGEMENT
        Route::resource('admins', AdminUserController::class);
        // E-R OFFICER ACCOUNTS MANAGEMENT
        Route::resource('officers', OfficerUserController::class);

        // STUDENT ACCOUNTS MANAGEMENT
        Route::get('students/data', [StudentUserController::class, 'getData'])->name('students.data');
        Route::resource('students', StudentUserController::class);

        // GENERIC USER ROUTES
        Route::get('data/{user_type}', [UserController::class, 'getData'])->name('users.data');
        Route::get('stats/{user_type}', [UserController::class, 'getStats'])->name('users.stats');
        Route::post('toggle-status/{id}', [UserController::class, 'toggle'])->middleware(PreventSelfAction::class)->name('users.toggle');
    });

    // DEPARTMENTS MANAGEMENT
    Route::resource('departments', DepartmentController::class)->except(['show']);
    Route::prefix('departments')->group(function () {

        Route::get('data', [DepartmentController::class, 'getData'])->name('departments.data');
        Route::get('stats', [DepartmentController::class, 'getStats'])->name('departments.stats');

        // FOR SELECT2
        Route::get('select', [DepartmentController::class, 'getDepartmentsForSelect'])->name('departments.select');
    });

    // PROGRAMS MANAGEMENT
    Route::resource('programs', ProgramController::class)->except(['show']);
    Route::prefix('programs')->group(function () {
        Route::get('data', [ProgramController::class, 'getData'])->name('programs.data');
        Route::get('stats', [ProgramController::class, 'getStats'])->name('programs.stats');

        // FOR SELECT2
        Route::get('select', [ProgramController::class, 'getProgramsForSelect'])->name('programs.select');
    });

    // CURRICULUM MANAGEMENT
    Route::resource('curricula', CurriculumController::class)->except(['show']);
    Route::prefix('curricula')->group(function () {
        Route::get('data', [CurriculumController::class, 'getData'])->name('curricula.data');
        Route::get('stats', [CurriculumController::class, 'getStats'])->name('curricula.stats');

        Route::post('toggle/{id}', [CurriculumController::class, 'toggle'])->name('curricula.toggle');

        Route::get('select/{program_id}', [CurriculumController::class, 'getCurriculaForSelect'])->name('curricula.select');

        // CURRICULUM SUBJECT MANAGEMENT
        Route::prefix('subjects')->group(function () {
            Route::get('/{curriculum_id}', [SubjectController::class, 'index'])->name('subjects.index');
            Route::get('data/{curriculum_id}', [SubjectController::class, 'getData'])->name('subjects.data');
            Route::get('stats/{curriculum_id}', [SubjectController::class, 'getStats'])->name('subjects.stats');
            Route::post('store/{curriculum_id}', [SubjectController::class, 'store'])->name('subjects.store');
            Route::get('edit/{id}', [SubjectController::class, 'edit'])->name('subjects.edit');
            Route::put('update/{id}', [SubjectController::class, 'update'])->name('subjects.update');
            Route::delete('destroy/{id}', [SubjectController::class, 'destroy'])->name('subjects.destroy');
            Route::post('toggle/{id}', [SubjectController::class, 'toggle'])->name('subjects.toggle');
        });
    });

});


Route::prefix('student')->middleware('auth')->group(function () {
    
    // ACADEMIC PROGRESS
    Route::get('academic-progress', [AcademicProgress::class, 'index'])->name('student.academic_progress.index');
    Route::get('academic-progress/data', [AcademicProgress::class, 'getData'])->name('student.academic_progress.data');
    Route::get('academic-progress/stats', [AcademicProgress::class, 'getStats'])->name('student.academic_progress.stats');

    // STUDENT MANUAL
    Route::view('manual', 'app.student_portal.manual.index')->name('student.manual.index');

    // FAQs
    Route::view('faqs', 'app.student_portal.general_information.faqs')->name('student.faqs.index');
    // Help
    Route::view('help', 'app.student_portal.general_information.help')->name('student.help.index');
});