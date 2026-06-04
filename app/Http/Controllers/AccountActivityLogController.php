<?php

namespace App\Http\Controllers;

use App\Models\AccountActivityLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controller AccountActivityLogController
 *
 * Hiển thị nhật ký hoạt động tài khoản cho người dùng đang đăng nhập.
 */
class AccountActivityLogController extends Controller
{
    /**
     * Danh sách nhật ký hoạt động của tài khoản hiện tại.
     */
    public function index(Request $request): View
    {
        $type = $request->string('type')->toString();

        $logs = AccountActivityLog::query()
            ->where('user_id', $request->user()->id)
            ->when($type !== '', function ($query) use ($type): void {
                $query->where('type', $type);
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('user.account-activity-logs', [
            'logs' => $logs,
            'type' => $type,
            'typeOptions' => [
                'login' => 'Đăng nhập',
                'profile_update' => 'Thay đổi hồ sơ',
                'purchase' => 'Mua hàng',
            ],
        ]);
    }
}
