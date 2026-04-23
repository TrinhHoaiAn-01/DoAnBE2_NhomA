<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
<<<<<<< HEAD
    // Login
    public function login(Request $request)
    {
        // 1. Validate input
=======
    // =========================
    // LOGIN
    // =========================
    public function login(Request $request)
    {
        // Validate input
>>>>>>> VAN_TRONG/Dang_ky
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6'],
        ]);

<<<<<<< HEAD
        // 2. Attempt login (có remember)
        $remember = $request->filled('remember');

=======
        // remember me
        $remember = $request->filled('remember');

        // Attempt login
>>>>>>> VAN_TRONG/Dang_ky
        if (Auth::attempt([
            'email' => $data['email'],
            'password' => $data['password']
        ], $remember)) {

<<<<<<< HEAD
            // 3. Fix session security
            $request->session()->regenerate();

            // 4. Redirect sau login
            return redirect()->intended('/home');
        }

        // 5. Login fail
=======
            // bảo mật session
            $request->session()->regenerate();

            return redirect()->intended('/home');
        }

>>>>>>> VAN_TRONG/Dang_ky
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