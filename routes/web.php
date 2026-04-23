<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// =========================
// HOME / WELCOME
// =========================
Route::get('/', function () {
    return view('welcome');
});

// =========================
// AUTH - LOGIN
// =========================

// Hiển thị form login
Route::get('/login', function () {
    return view('auth.login');
})->name('login.form');

// Xử lý login
Route::post('/login', [AuthController::class, 'login'])->name('login');

// =========================
// AUTH - REGISTER
// =========================

// Hiển thị form register
Route::get('/register', function () {
    return view('auth.register');
})->name('register.form');

// Xử lý register
Route::post('/register', [AuthController::class, 'register'])->name('register');

// =========================
// LOGOUT
// =========================
Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/login');
})->name('logout');

// =========================
// FORGOT PASSWORD
// =========================
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('password.request');

// =========================
// HOME (SAU LOGIN)
// =========================
Route::get('/home', function () {
    return "Login thành công";
})->middleware('auth');