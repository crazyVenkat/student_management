<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;


// Guest routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// Protected routes
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/students', [StudentController::class, 'index'])->name('students.index');
    Route::get('/students/list', [StudentController::class, 'list'])->name('students.list');
    Route::post('/students/store', [StudentController::class, 'store'])->name('students.store');
    Route::get('/students/{id}/edit', [StudentController::class, 'edit']);
    Route::post('/students/update/{id}', [StudentController::class, 'update']);
    Route::delete('/students/delete/{id}', [StudentController::class, 'destroy']);

    // Dependent dropdown
    Route::get('/programmes/{department_id}', [StudentController::class, 'getProgrammes']);
});

// Default redirect
Route::get('/', function () {
    return redirect('/login');
});
