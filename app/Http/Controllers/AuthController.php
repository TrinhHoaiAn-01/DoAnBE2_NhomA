<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // =========================
    // LOGIN
    // =========================
    public function login(Request $request)
    {
        // Validate input
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6'],
        ]);

        // remember me
        $remember = $request->filled('remember');

        // Attempt login
        if (Auth::attempt([
            'email' => $data['email'],
            'password' => $data['password']
        ], $remember)) {

            // bảo mật session
            $request->session()->regenerate();

            return redirect()->intended('/home');
        }

        return back()
            ->withErrors([
                'email' => 'Sai email hoặc mật khẩu'
            ])
            ->onlyInput('email');
    }

    // =========================
    // REGISTER
    // =========================
    public function register(Request $request)
    {
        // Validate input
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['required', 'min:6'],
            'role_id' => ['required', 'in:1,2'],
        ]);

        // Create user
        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'role_id' => $data['role_id'],
            'password' => Hash::make($data['password']),
        ]);

        // redirect về login sau khi đăng ký
        return redirect()->route('login.form')
            ->with('success', 'Đăng ký thành công! Hãy đăng nhập.');
    }
}