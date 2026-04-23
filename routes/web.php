<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Trang chủ
Route::get('/', function () {
    return view('welcome');
});

// --- Nhóm route Đăng nhập (Auth) ---
Route::get('/login', function () {
    return view('auth.login');
})->name('login.form');

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('password.request');

Route::get('/home', function () {
    return "Login thành công 🎉";
})->middleware('auth');


// --- Nhóm route Admin (Người 5) ---
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/permissions', [AdminController::class, 'permissions'])->name('permissions');
    Route::post('/permissions', [AdminController::class, 'updatePermissions'])->name('permissions.update');
    Route::get('/logs', [AdminController::class, 'logs'])->name('logs');
});
