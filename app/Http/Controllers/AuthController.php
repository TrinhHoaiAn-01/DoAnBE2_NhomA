<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Phần Login
    public function login(Request $request)
    {
        // 1. Validate
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6'],
        ]);

        // 2. Attempt login
        if (Auth::attempt($data)) {

            // 3. Bảo mật session
            $request->session()->regenerate();

            // 4. Redirect (nếu có intended thì dùng, không thì về home)
            return redirect()->intended('/home');
        }

        // 5. Thất bại
        return back()
            ->withErrors(['email' => 'Sai email hoặc mật khẩu'])
            ->onlyInput('email');
    }
}