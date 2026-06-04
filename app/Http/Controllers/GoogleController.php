<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        // Xóa session cũ
        Auth::logout();

        Session::flush();

        Session::invalidate();

        return Socialite::driver('google')
            ->with([
                'prompt' => 'select_account'
            ])
            ->stateless()
            ->redirect();
    }

    public function callback()
    {
        try {

            $googleUser = Socialite::driver('google')
                ->stateless()
                ->user();

            $email = $googleUser->getEmail();

            if (!$email) {
                return redirect('/login')
                    ->with('error', 'Google không cung cấp email.');
            }

            $user = User::where('email', $email)->first();

            if (!$user) {

                $baseUsername = explode('@', $email)[0];

                $username = $baseUsername;
                $index = 1;

                while (User::where('username', $username)->exists()) {
                    $username = $baseUsername . $index;
                    $index++;
                }

                $user = User::create([
                    'name' => $googleUser->getName(),
                    'username' => $username,
                    'email' => $email,
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'avatar_url' => $googleUser->getAvatar(),
                    'password' => bcrypt(uniqid()),
                    'role_id' => 2,
                    'status' => 1,
                ]);
            }

            Auth::logout();

            request()->session()->invalidate();

            request()->session()->regenerateToken();

            Auth::login($user, true);

            request()->session()->regenerate();

            return redirect('/');

        } catch (\Exception $e) {

            return redirect('/login')
                ->with('error', $e->getMessage());
        }
    }
}