<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ... (giữ nguyên các route login bên dưới)

// --- Nhóm route Admin (Người 5) ---
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Quản lý Nhà cung cấp
    Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
    Route::post('/suppliers', [SupplierController::class, 'store'])->name('suppliers.store');
    Route::delete('/suppliers/{id}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');

    Route::get('/permissions', [AdminController::class, 'permissions'])->name('permissions');
    Route::post('/permissions', [AdminController::class, 'updatePermissions'])->name('permissions.update');
    Route::get('/logs', [AdminController::class, 'logs'])->name('logs');
});
