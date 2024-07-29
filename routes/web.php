<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Route untuk login form
Route::get('/login', [AuthController::class, 'viewLogin'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

// Route untuk logout
Route::post('/logout', [AuthController::class, 'logout'])->middleware('authorization')->name('logout');

// Route yang memerlukan middleware authorization
Route::middleware(['authorization'])->group(function () {
    Route::get('/', function () {
        return view('welcome');
    })->name('home'); // Tambahkan nama route untuk home

    // Route lainnya yang memerlukan token
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

// Route lainnya jika diperlukan
