<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\CheckRole;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

/*
|--------------------------------------------------------------------------
| WEB ROUTES
|--------------------------------------------------------------------------
*/

// =========================
// HOME
// =========================
Route::get('/', fn () => view('welcome'));


// =========================
// AUTH
// =========================
Route::middleware('guest')->group(function () {

    Route::get('/login', fn () => view('auth.login'))
        ->name('login.form');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login');

    Route::get('/register', fn () => view('auth.register'))
        ->name('register.form');

    Route::post('/register', [AuthController::class, 'register'])
        ->name('register');
});


// =========================
// LOGOUT
// =========================
Route::post('/logout', function () {

    Auth::logout();

    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect()->route('login.form');

})->middleware('auth')->name('logout');


// =========================
// FORGOT PASSWORD (FAKE - FIXED)
// =========================
Route::get('/forgot-password', fn () => view('auth.forget-password'))
    ->middleware('guest')
    ->name('password.request');

Route::post('/forgot-password', function (Request $request) {

    $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required', 'min:6', 'confirmed'],
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return back()->withErrors([
            'email' => 'Email không tồn tại'
        ]);
    }

    $user->password = Hash::make($request->password);
    $user->save();

    return redirect()->route('login.form')
        ->with('success', 'Đổi mật khẩu thành công!');

})->middleware('guest')->name('password.update.fake');


// =========================
// DASHBOARD (USER)
// =========================
Route::get('/home', function () {
    return view('admin.dashboard');
})->middleware('auth')->name('home');


// =========================
// ADMIN ONLY ROUTES (FIX IMPORTANT)
// =========================
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', CheckRole::class . ':1']) // 1 = admin
    ->group(function () {

        Route::get('/dashboard', [AdminController::class, 'dashboard'])
            ->name('dashboard');

        // Suppliers
        Route::get('/suppliers', [SupplierController::class, 'index'])
            ->name('suppliers.index');

        Route::post('/suppliers', [SupplierController::class, 'store'])
            ->name('suppliers.store');

        Route::delete('/suppliers/{id}', [SupplierController::class, 'destroy'])
            ->name('suppliers.destroy');

        // Permissions
        Route::get('/permissions', [AdminController::class, 'permissions'])
            ->name('permissions');

        Route::post('/permissions', [AdminController::class, 'updatePermissions'])
            ->name('permissions.update');

        // Logs
        Route::get('/logs', [AdminController::class, 'logs'])
            ->name('logs');
    });