<?php
namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactSubmitController extends Controller
{
    // Hàm lưu thông tin liên hệ gửi từ form trang chủ
    public function store(Request $request)
    {
        // Kiểm tra dữ liệu nhập vào
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ], [
            'name.required' => 'Vui lòng nhập họ tên.',
            'email.required' => 'Vui lòng nhập địa chỉ email.',
            'email.email' => 'Email không đúng định dạng.',
            'subject.required' => 'Vui lòng nhập chủ đề liên hệ.',
            'message.required' => 'Vui lòng nhập nội dung lời nhắn.',
        ]);

        // Tạo mới liên hệ trong DB
        Contact::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => 'pending', // mặc định là chờ xử lý
        ]);

        // Quay lại trang trước và báo thành công
        return redirect()->back()->with('success', 'Gửi liên hệ thành công! Chúng tôi sẽ xử lý và phản hồi bạn sớm nhất.');
    }
}
