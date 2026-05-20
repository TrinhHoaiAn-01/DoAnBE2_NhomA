<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {

            return back()
                ->withInput($request->except('password'))
                ->withErrors([
                    'email' => 'Email và Mật khẩu không đúng!'
                ]);
        }

        $request->session()->regenerate();

        // CHECK STATUS
		if (!Auth::user()?->status) {

			Auth::logout();

			$request->session()->invalidate();

			$request->session()->regenerateToken();

			return back()->withErrors([
				'email' => 'Tài khoản này đã bị khoá!'
			]);
		}

        return redirect()
            ->intended(route('home'));
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'confirmed', 'min:8'],
            'role_id' => ['required', 'integer', 'in:1,2'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password' => Hash::make($data['password']),
            'role_id' => $data['role_id'],
            'status' => 'active',
        ]);

        Auth::login($user);

        $request->session()->regenerate();

        return to_route('home')
            ->with('status', 'Dang ky tai khoan thanh cong.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return to_route('home')
            ->with('status', 'Da dang xuat.');
    }
}
