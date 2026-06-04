<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class SupportUserController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | HIỂN THỊ TRANG SUPPORT
    |--------------------------------------------------------------------------
    */
    public function index(): View
    {
        return view('user.profile-user', [
            'profileSection' => 'support',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | GỬI EMAIL HỖ TRỢ
    |--------------------------------------------------------------------------
    */
    public function send(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'issue_type' => ['required', 'string', 'in:account,order,payment,system,other'],
            'description' => ['required', 'string', 'min:10', 'max:5000'],
        ], [], [
            'email' => 'email người dùng',
            'issue_type' => 'loại lỗi',
            'description' => 'mô tả lỗi',
        ]);

        $issueLabels = [
            'account' => 'Lỗi tài khoản',
            'order' => 'Lỗi đơn hàng',
            'payment' => 'Lỗi thanh toán',
            'system' => 'Lỗi hệ thống',
            'other' => 'Lỗi khác',
        ];

        $user = $request->user();
        $adminEmail = config('mail.from.address', 'trinhhoaia03@gmail.com');
        $issueLabel = $issueLabels[$validated['issue_type']];

        $content = trim("
YÊU CẦU HỖ TRỢ NGƯỜI DÙNG

Họ tên: " . ($user->name ?: $user->username) . "
Tên đăng nhập: {$user->username}
ID người dùng: {$user->id}
Email người dùng: {$validated['email']}
Loại lỗi: {$issueLabel}

Mô tả lỗi:
{$validated['description']}
        ");

        try {
            Mail::raw($content, function ($mail) use ($adminEmail, $validated, $issueLabel) {
                $mail->to($adminEmail)
                    ->replyTo($validated['email'])
                    ->subject("Yêu cầu hỗ trợ: {$issueLabel}");
            });
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Không thể gửi email. Vui lòng thử lại sau.');
        }

        return back()->with('success', 'Gửi thành công');
    }
}
