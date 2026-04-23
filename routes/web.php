<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Controllers\AuthController;

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

    return redirect()->route('login.form');
})->name('logout');


// =========================
// FORGOT PASSWORD (fake reset)
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
// DASHBOARD (AFTER LOGIN)
// =========================
Route::get('/home', function () {
    return view('admin.dashboard');
})->middleware('auth')->name('home');