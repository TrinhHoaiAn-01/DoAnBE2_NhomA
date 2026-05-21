<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\SystemLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    /* =========================
        SHOW LOGIN
    ========================= */
    public function showLogin(): View
    {
        return view('auth.login');
    }

    /* =========================
        LOGIN
    ========================= */
    public function login(Request $request): RedirectResponse
    {
        // 1. Validate
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // 2. Remember me
        $remember = $request->boolean('remember');

        // 3. Attempt login
        if (!Auth::attempt($credentials, $remember)) {
            return back()
                ->withInput($request->except('password'))
                ->withErrors([
                    'email' => 'Email hoặc mật khẩu không đúng!'
                ]);
        }

        // 4. Regenerate session
        $request->session()->regenerate();

        // 5. Check status
        $user = Auth::user();

        if (!$user || !$user->status) {

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors([
                'email' => 'Tài khoản đã bị khóa!'
            ]);
        }

        // 6. Log activity
        SystemLog::create([
            'user_name' => $user->name,
            'action' => 'Đăng nhập hệ thống',
            'target_type' => 'Tài khoản',
            'old_data' => null,
            'new_data' => [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ],
        ]);

        // 7. Redirect
        return redirect()->intended(route('home'));
    }

    /* =========================
        SHOW REGISTER
    ========================= */
    public function showRegister(): View
    {
        return view('auth.register');
    }

    /* =========================
        REGISTER
    ========================= */
    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'avatar_url' => ['nullable', 'string', 'max:255'],
            'home_address' => ['nullable', 'string'],
            'gender' => ['nullable', 'in:male,female,other'],
            'date_of_birth' => ['nullable', 'date'],
            'password' => ['required', 'string', 'confirmed', 'min:8'],
        ]);

        $user = User::create([
            'name' => $data['name'] ?? $data['username'],
            'username' => $data['username'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'avatar_url' => $data['avatar_url'] ?? null,
            'home_address' => $data['home_address'] ?? null,
            'gender' => $data['gender'] ?? null,
            'date_of_birth' => $data['date_of_birth'] ?? null,
            'password' => Hash::make($data['password']),
            'role_id' => 1,
            'status' => true,
        ]);

        Auth::login($user);
        Auth::logout();

        return redirect()->route('login')
            ->with('success', 'Đăng ký thành công!');
    }

    /* =========================
        LOGOUT
    ========================= */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /* =========================
        FORGET PASSWORD
    ========================= */
    public function showForgetPassword(): View
    {
        return view('auth.forget-password');
    }

    public function forgetPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6', 'confirmed'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Email không tồn tại!'
            ]);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()
            ->route('login')
            ->with('success', 'Đổi mật khẩu thành công!');
    }
}