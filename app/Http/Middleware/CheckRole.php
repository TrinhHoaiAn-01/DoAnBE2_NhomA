<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware CheckRole
 *
 * Kiểm tra vai trò (role_id) của người dùng để quyết định quyền truy cập vào route.
 */
class CheckRole
{
    /**
     * Xử lý yêu cầu HTTP được gửi đến (Incoming Request).
     *
     * @param  \Illuminate\Http\Request  $request  Đối tượng yêu cầu HTTP hiện tại
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next  Hàm closure xử lý tiếp theo trong pipeline
     * @param  string  ...$roles  Danh sách các ID vai trò (role_id) được phép truy cập
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // 1. Kiểm tra xem người dùng đã đăng nhập chưa
        // Nếu chưa đăng nhập, chuyển hướng người dùng đến trang đăng nhập
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // 2. Lấy role_id của người dùng hiện tại và chuyển về dạng chuỗi (string) để so sánh
        $userRole = (string) Auth::user()->role_id;

        // 3. Kiểm tra xem role_id của người dùng có nằm trong danh sách các vai trò được phép hay không
        // Nếu không có quyền truy cập, trả về lỗi 403 (Truy cập bị hạn chế)
        if (!in_array($userRole, $roles, true)) {
            abort(403, 'Truy cập bị hạn chế!');
        }

        // 4. Nếu hợp lệ, cho phép yêu cầu tiếp tục đi tiếp qua middleware tiếp theo hoặc vào Controller
        return $next($request);
    }
}