<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;

use App\Http\Middleware\CheckRole;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

use App\Models\User;

/*
|--------------------------------------------------------------------------
| WEB ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/', HomeController::class)->name('home');

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function (): void {

    Route::get('/dang-nhap', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/dang-nhap', [AuthController::class, 'login'])
        ->name('login.submit');

    Route::get('/dang-ky', [AuthController::class, 'showRegister'])
        ->name('register');

    Route::post('/dang-ky', [AuthController::class, 'register'])
        ->name('register.submit');

    Route::get('/quen-mat-khau', function () {
        return view('auth.forget-password');
    })->name('password.request');

    Route::post('/quen-mat-khau', function (Request $request) {

        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6', 'confirmed'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Email khong ton tai'
            ]);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()
            ->route('login')
            ->with('success', 'Doi mat khau thanh cong!');
    })->name('password.update.fake');
});

Route::middleware('auth')->group(function (): void {

    Route::post('/dang-xuat', [AuthController::class, 'logout'])
        ->name('logout');
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', CheckRole::class . ':1'])
    ->group(function (): void {

        Route::get('/dashboard', [AdminController::class, 'dashboard'])
            ->name('dashboard');

        Route::get('/suppliers', [SupplierController::class, 'index'])
            ->name('suppliers.index');

        Route::post('/suppliers', [SupplierController::class, 'store'])
            ->name('suppliers.store');

        Route::delete('/suppliers/{id}', [SupplierController::class, 'destroy'])
            ->name('suppliers.destroy');

        Route::resource('categories', CategoryController::class)
            ->except(['show', 'create', 'edit']);

        Route::resource('products', ProductController::class)
            ->except(['show', 'create', 'edit']);

        Route::get('/permissions', [AdminController::class, 'permissions'])
            ->name('permissions');

        Route::post('/permissions', [AdminController::class, 'updatePermissions'])
            ->name('permissions.update');

        Route::get('/logs', [AdminController::class, 'logs'])
            ->name('logs');
    });