<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StaffController;


// Guest routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// Protected routes
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    /* Students */
    Route::prefix('students')->name('students.')->group(function () {
        Route::get('/', [StudentController::class, 'index'])->name('index');
        Route::get('/list', [StudentController::class, 'list'])->name('list');
        Route::post('/store', [StudentController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [StudentController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [StudentController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [StudentController::class, 'destroy'])->name('destroy');
        Route::post('/import', [StudentController::class, 'importStudents'])->name('import');
    });

    /* Staffs */
    Route::prefix('staff')->name('staff.')->group(function () {
        Route::get('/', [StaffController::class, 'index'])->name('index');
        Route::get('/list', [StaffController::class, 'list'])->name('list');
        Route::post('/store', [StaffController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [StaffController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [StaffController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [StaffController::class, 'destroy'])->name('destroy');
    });

    // Dependent dropdown
    Route::get('/programmes/{department_id}', [StudentController::class, 'getProgrammes']);
});

