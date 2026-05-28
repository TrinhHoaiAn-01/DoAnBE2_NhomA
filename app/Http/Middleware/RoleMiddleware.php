<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Middleware RoleMiddleware
 *
 * Kiểm soát quyền truy cập dựa trên vai trò phân loại tổng quát (admin/user).
 */
class RoleMiddleware
{
    /**
     * Xử lý yêu cầu HTTP được gửi đến (Incoming Request).
     *
     * @param  \Illuminate\Http\Request  $request  Đối tượng yêu cầu HTTP hiện tại
     * @param  \Closure  $next  Hàm closure xử lý tiếp theo trong pipeline
     * @param  string  $role  Vai trò yêu cầu (ví dụ: 'admin' hoặc 'user')
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        // 1. Lấy thông tin người dùng đang đăng nhập
        $user = Auth::user();

        // 2. Nếu người dùng chưa đăng nhập, chuyển hướng đến trang đăng nhập
        if (!$user) {
            return redirect()->route('login');
        }

        // 3. Nếu route yêu cầu vai trò 'admin' mà vai trò của user không phải 5 (không phải admin)
        // role_id 5 được định nghĩa là Admin
        if ($role === 'admin' && $user->role_id != 5) {
            abort(403, 'Bạn không có quyền truy cập');
        }

        // 4. Nếu route yêu cầu vai trò 'user' (người dùng thông thường, role_id từ 1-4)
        // mà vai trò của user lại là 5 (admin), thì chặn truy cập
        if ($role === 'user' && $user->role_id == 5) {
            abort(403, 'Bạn không có quyền truy cập');
        }

        // 5. Nếu vượt qua các bước kiểm tra, cho phép đi tiếp
        return $next($request);
    }
}