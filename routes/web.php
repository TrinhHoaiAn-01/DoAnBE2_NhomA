<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\AuthController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// =========================
// HOME
// =========================
Route::get('/', function () {
    return view('welcome');
});

// =========================
// LOGIN
// =========================
Route::get('/login', function () {
    return view('auth.login');
})->name('login.form');

Route::post('/login', [AuthController::class, 'login'])->name('login');


// =========================
// REGISTER
// =========================
Route::get('/register', function () {
    return view('auth.register');
})->name('register.form');

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
    return view('auth.forget-password');
})->name('password.request');

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
})->name('password.update.fake');


// =========================
// HOME (AFTER LOGIN)
// =========================
Route::get('/home', function () {
    return "Login thành công";
})->middleware('auth');


// =========================
// ADMIN ROUTES (NGƯỜI 5)
// =========================
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
