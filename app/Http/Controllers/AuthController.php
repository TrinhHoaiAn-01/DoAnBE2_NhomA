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
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6'],
        ]);

        $remember = $request->filled('remember');

        if (Auth::attempt([
            'email' => $data['email'],
            'password' => $data['password']
        ], $remember)) {

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
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'phone'    => ['required', 'string', 'max:20'],
            'password' => ['required', 'min:6'],
            'role_id'  => ['required', 'in:1,2'],
        ]);

        // dd($data);
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'phone'    => $data['phone'],
            'password' => Hash::make($data['password']),
            'role_id'  => (int) $data['role_id'], 
        ]);

        // dd($user->role_id);
        return redirect()->route('login.form')
            ->with('success', 'Đăng ký thành công! Hãy đăng nhập.');
    }
}