<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::middleware('guest')->group(function (): void {
    Route::get('/dang-nhap', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/dang-nhap', [AuthController::class, 'login'])->name('login.submit');
    Route::get('/dang-ky', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/dang-ky', [AuthController::class, 'register'])->name('register.submit');
});

Route::middleware('auth')->group(function (): void {
    Route::post('/dang-xuat', [AuthController::class, 'logout'])->name('logout');
});

Route::prefix('admin')->name('admin.')->group(function (): void {
    Route::resource('categories', CategoryController::class)
        ->except(['show', 'create', 'edit']);
    Route::resource('products', ProductController::class)
        ->except(['show', 'create', 'edit']);
});
