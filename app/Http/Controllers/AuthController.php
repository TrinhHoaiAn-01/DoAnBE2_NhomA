<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Login
    public function login(Request $request)
    {
        // 1. Validate input
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6'],
        ]);

        // 2. Attempt login (có remember)
        $remember = $request->filled('remember');

        if (Auth::attempt([
            'email' => $data['email'],
            'password' => $data['password']
        ], $remember)) {

            // 3. Fix session security
            $request->session()->regenerate();

            // 4. Redirect sau login
            return redirect()->intended('/home');
        }

        // 5. Login fail
        return back()
            ->withErrors([
                'email' => 'Sai email hoặc mật khẩu'
            ])
            ->onlyInput('email');
    }
}