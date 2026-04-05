<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Guest routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// Protected routes
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Default redirect
Route::get('/', function () {
    return redirect('/login');
});
