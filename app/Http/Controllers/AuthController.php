<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\SystemLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

/**
 * Controller AuthController
 *
 * Xử lý các nghiệp vụ liên quan đến xác thực người dùng bao gồm:
 * Đăng nhập, Đăng ký, Đăng xuất, Khôi phục/Đổi mật khẩu và ghi nhận nhật ký hệ thống.
 */
class AuthController extends Controller
{
    /**
     * Hiển thị trang đăng nhập.
     *
     * @return \Illuminate\View\View
     */
    public function showLogin(): View
    {
        return view('auth.login');
    }

    /**
     * Xử lý yêu cầu đăng nhập hệ thống.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request): RedirectResponse
    {
        // 1. Kiểm tra tính hợp lệ của dữ liệu đầu vào (Validation)
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // 2. Lấy tùy chọn "Ghi nhớ đăng nhập" (Remember me)
        $remember = $request->boolean('remember');

        // 3. Thực hiện thử đăng nhập với thông tin người dùng cung cấp
        if (!Auth::attempt($credentials, $remember)) {
            // Đăng nhập thất bại: Quay lại trang trước, giữ lại email và thông báo lỗi
            return back()
                ->withInput($request->except('password'))
                ->withErrors([
                    'email' => 'Email hoặc mật khẩu không đúng!'
                ]);
        }

        // 4. Đăng nhập thành công: Tạo lại session ID để tránh tấn công Session Fixation
        $request->session()->regenerate();

        // 5. Kiểm tra trạng thái tài khoản người dùng
        $user = Auth::user();

        // Nếu tài khoản bị vô hiệu hóa (status = false/0)
        if (!$user || !$user->status) {
            // Đăng xuất ngay lập tức, hủy session và token bảo mật
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors([
                'email' => 'Tài khoản đã bị khóa!'
            ]);
        }

        // 6. Ghi lại nhật ký hoạt động đăng nhập thành công vào bảng SystemLog
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

        // 7. Chuyển hướng người dùng về trang chủ hoặc route mà họ định truy cập trước đó (intended)
        return redirect()->intended(route('home'));
    }

    /**
     * Hiển thị trang đăng ký tài khoản mới.
     *
     * @return \Illuminate\View\View
     */
    public function showRegister(): View
    {
        return view('auth.register');
    }

    /**
     * Xử lý yêu cầu đăng ký tài khoản người dùng mới.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request): RedirectResponse
    {
        // 1. Kiểm tra tính hợp lệ của dữ liệu đăng ký
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

        // 2. Tạo bản ghi người dùng mới trong cơ sở dữ liệu
        $user = User::create([
            'name' => $data['name'] ?? $data['username'],
            'username' => $data['username'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'avatar_url' => $data['avatar_url'] ?? null,
            'home_address' => $data['home_address'] ?? null,
            'gender' => $data['gender'] ?? null,
            'date_of_birth' => $data['date_of_birth'] ?? null,
            'password' => Hash::make($data['password']), // Mã hóa mật khẩu
            'role_id' => 1, // Mặc định gán vai trò là Khách hàng (role_id = 1)
            'status' => true, // Trạng thái hoạt động mặc định là kích hoạt
        ]);

        // 3. Thực hiện đăng nhập rồi lập tức đăng xuất (để thiết lập session ban đầu nếu cần)
        // và yêu cầu người dùng tự đăng nhập lại tại trang đăng nhập
        Auth::login($user);
        Auth::logout();

        return redirect()->route('login')
            ->with('success', 'Đăng ký thành công!');
    }

    /**
     * Xử lý yêu cầu đăng xuất khỏi hệ thống.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request): RedirectResponse
    {
        // 1. Đăng xuất tài khoản khỏi Guard hiện tại
        Auth::logout();

        // 2. Hủy toàn bộ thông tin Session hiện tại
        $request->session()->invalidate();

        // 3. Tạo lại token CSRF mới để chống tấn công giả mạo yêu cầu chéo trang
        $request->session()->regenerateToken();

        // 4. Chuyển hướng về trang đăng nhập
        return redirect()->route('login');
    }

    /**
     * Hiển thị trang khôi phục/đổi mật khẩu khi quên.
     *
     * @return \Illuminate\View\View
     */
    public function showForgetPassword(): View
    {
        return view('auth.forget-password');
    }

    /**
     * Xử lý yêu cầu khôi phục/đặt lại mật khẩu mới.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function forgetPassword(Request $request): RedirectResponse
    {
        // 1. Validate thông tin nhập vào (email và mật khẩu mới kèm xác nhận)
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6', 'confirmed'],
        ]);

        // 2. Tìm người dùng theo địa chỉ email cung cấp
        $user = User::where('email', $request->email)->first();

        // Nếu email không tồn tại trong hệ thống, quay lại với thông báo lỗi
        if (!$user) {
            return back()->withErrors([
                'email' => 'Email không tồn tại!'
            ]);
        }

        // 3. Tiến hành cập nhật mật khẩu mới (đã mã hóa bằng Bcrypt/Hash)
        $user->password = Hash::make($request->password);
        $user->save();

        // 4. Chuyển hướng người dùng về trang đăng nhập kèm thông báo thành công
        return redirect()
            ->route('login')
            ->with('success', 'Đổi mật khẩu thành công!');
    }
}