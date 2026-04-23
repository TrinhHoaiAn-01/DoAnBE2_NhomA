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

// form login
Route::get('/login', function () {
    return view('auth.login');
})->name('login.form');

// xử lý login
Route::post('/login', [AuthController::class, 'login'])->name('login');


// =========================
// REGISTER
// =========================

// form register
Route::get('/register', function () {
    return view('auth.register');
})->name('register.form');

// xử lý register
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

// form forgot password
Route::get('/forgot-password', function () {
    return view('auth.forget-password');
})->name('password.request');


// xử lý đổi mật khẩu (fake reset)
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