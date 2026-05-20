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
                    'email' => 'Thong tin dang nhap khong hop le.'
                ]);
        }

        $request->session()->regenerate();

        // CHECK STATUS
        if (Auth::user()?->status !== 'active') {

            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors([
                'email' => 'Tai khoan tam thoi khong duoc phep dang nhap.'
            ]);
        }

        return redirect()
            ->intended(route('home'))
            ->with('status', 'Dang nhap thanh cong.');
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
	{
		$data = $request->validate([

			// profile
			'name' => ['nullable', 'string', 'max:255'],

			'username' => [
				'required',
				'string',
				'max:255',
				'unique:users,username'
			],

			'email' => [
				'required',
				'email',
				'max:255',
				'unique:users,email'
			],

			'phone' => [
				'nullable',
				'string',
				'max:20'
			],

			'avatar_url' => [
				'nullable',
				'string',
				'max:255'
			],

			'home_address' => [
				'nullable',
				'string'
			],

			// gender
			'gender' => [
				'nullable',
				'in:male,female,other'
			],

			'date_of_birth' => [
				'nullable',
				'date'
			],

			// auth
			'password' => [
				'required',
				'string',
				'confirmed',
				'min:8'
			],

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

			// default
			'role_id' => 2,

			// boolean
			'status' => true,
		]);

		Auth::login($user);

		$request->session()->regenerate();

		return to_route('home')
			->with('status', 'Đăng ký thành công!');
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
