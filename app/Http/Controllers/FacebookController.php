<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class FacebookController extends Controller
{
    /**
     * Chuyển hướng sang Facebook Login
     */
    public function redirect()
    {
        return Socialite::driver('facebook')
            ->stateless()
            ->redirect();
    }

    /**
     * Callback từ Facebook
     */
    public function callback()
    {
        try {

            $facebookUser = Socialite::driver('facebook')
                ->stateless()
                ->user();

            $email = $facebookUser->getEmail();

            if (empty($email)) {
                return redirect()
                    ->route('login')
                    ->with(
                        'error',
                        'Facebook không cung cấp email cho tài khoản này.'
                    );
            }

            // Tìm user theo email
            $user = User::where('email', $email)->first();

            // Nếu chưa tồn tại thì tạo mới
            if (!$user) {

                $baseUsername = explode('@', $email)[0];
                $username = $baseUsername;
                $index = 1;

                while (
                    User::where('username', $username)->exists()
                ) {
                    $username = $baseUsername . $index;
                    $index++;
                }

                $user = User::create([
                    'name' => $facebookUser->getName() ?: $username,
                    'username' => $username,
                    'email' => $email,
                    'password' => bcrypt(Str::random(32)),
                    'avatar_url' => $facebookUser->getAvatar(),
                    'role_id' => 2,
                    'status' => 1,
                ]);
            } else {

                // Cập nhật avatar mới nhất
                $user->update([
                    'avatar_url' => $facebookUser->getAvatar(),
                ]);
            }

            // Kiểm tra trạng thái tài khoản
            if (
                isset($user->status) &&
                (int) $user->status !== 1
            ) {
                return redirect()
                    ->route('login')
                    ->with(
                        'error',
                        'Tài khoản của bạn đã bị khóa.'
                    );
            }

            // Logout tài khoản hiện tại
            Auth::logout();

            request()->session()->invalidate();
            request()->session()->regenerateToken();

            // Login tài khoản Facebook
            Auth::login($user, true);

            request()->session()->regenerate();

            return redirect()->intended('/');

        } catch (\Throwable $e) {

            Log::error('Facebook Login Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->route('login')
                ->with(
                    'error',
                    'Đăng nhập Facebook thất bại. Vui lòng thử lại.'
                );
        }
    }
}