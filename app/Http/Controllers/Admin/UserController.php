<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controller UserController (Admin)
 *
 * Quản lý danh sách thành viên (User) trong hệ thống admin.
 * Cung cấp các tính năng: Xem danh sách thành viên (lọc theo từ khóa, vai trò, trạng thái khóa),
 * cập nhật vai trò (phân quyền vai trò hệ thống), và thay đổi trạng thái hoạt động/khóa tài khoản.
 * Có ràng buộc bảo mật ngăn chặn Admin tự khóa tài khoản của chính mình khi đang đăng nhập.
 */
class UserController extends Controller
{
    /**
     * Hiển thị danh sách thành viên kèm bộ lọc nâng cao và thống kê số lượng thành viên nhanh.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request): View
    {
        // 1. Nhận các tham số tìm kiếm và lọc dữ liệu
        $search = trim((string) $request->string('search'));
        $roleId = $request->integer('role_id');
        $status = $request->input('status');

        // 2. Thực hiện truy vấn danh sách người dùng
        $users = User::query()
            ->when($search !== '', function ($query) use ($search): void {
                // Lọc theo họ tên, email hoặc số điện thoại
                $query->where(function ($nested) use ($search): void {
                    $nested
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->when($roleId > 0, function ($query) use ($roleId): void {
                // Lọc theo ID vai trò
                $query->where('role_id', $roleId);
            })
            ->when($status !== null && $status !== '', function ($query) use ($status): void {
                // Lọc theo trạng thái hoạt động (true/false)
                $query->where('status', (bool)$status);
            })
            ->latest() // Tài khoản mới đăng ký lên đầu
            ->paginate(15) // Phân trang 15 tài khoản/trang
            ->withQueryString();

        return view('admin.users', [
            'users' => $users,
            'search' => $search,
            'roleId' => $roleId,
            'status' => $status,
            'roleOptions' => $this->roleOptions(), // Tên gọi các vai trò bằng tiếng Việt
            'statusOptions' => $this->statusOptions(), // Tên gọi các trạng thái bằng tiếng Việt
            // Thống kê nhanh số lượng thành viên
            'adminCount' => User::query()->where('role_id', 5)->count(), // Số lượng admin
            'customerCount' => User::query()->where('role_id', 1)->count(), // Số lượng khách hàng
            'lockedCount' => User::query()->where('status', false)->count(), // Số lượng tài khoản bị khóa
        ]);
    }

    /**
     * Xử lý cập nhật thông tin vai trò và trạng thái hoạt động của một thành viên.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user Đối tượng người dùng cần chỉnh sửa
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        // 1. Xác thực thông tin phân quyền và trạng thái
        $data = $request->validate([
            'role_id' => ['required', 'integer', 'in:1,2,3,4,5'], // Phải nằm trong danh sách vai trò hợp lệ (1-5)
            'status' => ['required', 'in:0,1'], // Chỉ nhận 0 (khóa) hoặc 1 (hoạt động)
        ]);

        $statusVal = (bool)$data['status'];

        // Bảo mật: Không cho phép Admin tự khóa tài khoản của chính mình đang đăng nhập hiện tại
        if ($request->user()?->is($user) && !$statusVal) {
            return to_route('admin.users.index')->with('error', 'Không thể khóa tài khoản đang đăng nhập.');
        }

        // 2. Tiến hành cập nhật thông tin trong Database
        $user->update([
            'role_id' => $data['role_id'],
            'status' => $statusVal,
        ]);

        return to_route('admin.users.index')->with('status', 'Đã cập nhật người dùng.');
    }

    /**
     * Danh sách các vai trò thành viên dịch sang tiếng Việt.
     *
     * @return array
     */
    private function roleOptions(): array
    {
        return [
            5 => 'Quản trị viên',
            4 => 'Nhân viên Kho',
            3 => 'Nhân viên Đơn hàng',
            2 => 'Nhân viên Sản phẩm',
            1 => 'Khách hàng',
        ];
    }

    /**
     * Danh sách các trạng thái tài khoản dịch sang tiếng Việt.
     *
     * @return array
     */
    private function statusOptions(): array
    {
        return [
            1 => 'Đang hoạt động',
            0 => 'Đã khóa',
        ];
    }
}

