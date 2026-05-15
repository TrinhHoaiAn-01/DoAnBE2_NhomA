<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->string('search'));
        $roleId = $request->integer('role_id');
        $status = $request->string('status')->toString();

        $users = User::query()
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($nested) use ($search): void {
                    $nested
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->when($roleId > 0, function ($query) use ($roleId): void {
                $query->where('role_id', $roleId);
            })
            ->when($status !== '', function ($query) use ($status): void {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'search' => $search,
            'roleId' => $roleId,
            'status' => $status,
            'roleOptions' => $this->roleOptions(),
            'statusOptions' => $this->statusOptions(),
            'adminCount' => User::query()->where('role_id', 1)->count(),
            'customerCount' => User::query()->where('role_id', 2)->count(),
            'lockedCount' => User::query()->where('status', 'locked')->count(),
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'role_id' => ['required', 'integer', 'in:1,2,3,4,5'],
            'status' => ['required', 'in:active,locked'],
        ]);

        if ($request->user()?->is($user) && $data['status'] === 'locked') {
            return to_route('admin.users.index')->with('error', 'Không thể khóa tài khoản đang đăng nhập.');
        }

        $user->update($data);

        return to_route('admin.users.index')->with('status', 'Đã cập nhật người dùng.');
    }

    private function roleOptions(): array
    {
        return [
            1 => 'Quản trị viên',
            2 => 'Khách hàng',
            3 => 'Bán hàng',
            4 => 'Kho vận',
            5 => 'Hỗ trợ',
        ];
    }

    private function statusOptions(): array
    {
        return [
            'active' => 'Đang hoạt động',
            'locked' => 'Đã khóa',
        ];
    }
}
