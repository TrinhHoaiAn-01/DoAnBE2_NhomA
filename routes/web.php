<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Trang chủ
Route::get('/', function () {
    return view('welcome');
});

// Hiển thị login
Route::get('/login', function () {
    return view('auth.login');
})->name('login.form');

// Xử lý login
Route::post('/login', [AuthController::class, 'login'])->name('login');

// Quên mật khẩu
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('password.request');

// Trang sau khi login
Route::get('/home', function () {
    return "Login thành công 🎉";
})->middleware('auth');