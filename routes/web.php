<?php

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Admin Routes (Người 5)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/permissions', [AdminController::class, 'permissions'])->name('permissions');
    Route::get('/logs', [AdminController::class, 'logs'])->name('logs');
});
