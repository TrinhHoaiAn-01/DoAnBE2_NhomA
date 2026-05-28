<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

/**
 * Controller ContactController
 *
 * Quản lý các tin nhắn liên hệ (Contact) từ khách hàng gửi về hệ thống.
 * Hỗ trợ các chức năng: Xem danh sách các tin nhắn liên hệ (phân trang, lọc theo trạng thái),
 * xem nội dung chi tiết tin nhắn, và gửi phản hồi giải quyết thắc mắc (chuyển trạng thái sang resolved).
 */
class ContactController extends Controller
{
    /**
     * Hiển thị danh sách liên hệ từ khách hàng kèm bộ lọc trạng thái.
     * Thống kê số lượng liên hệ chưa xử lý (pending) và đã xử lý (resolved).
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Contact::query();
        
        // Lọc theo trạng thái liên hệ nếu được chọn
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Phân trang danh sách liên hệ mới nhất lên trước (15 liên hệ/trang)
        $contacts = $query->latest()->paginate(15);
        
        // Lấy số lượng thống kê nhanh các liên hệ theo trạng thái
        $pendingCount = Contact::where('status', 'pending')->count();
        $resolvedCount = Contact::where('status', 'resolved')->count();
        
        return view('admin.contacts.index', compact('contacts', 'pendingCount', 'resolvedCount'));
    }

    /**
     * Hiển thị chi tiết nội dung tin nhắn liên hệ của khách hàng.
     *
     * @param int $id ID của liên hệ
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $contact = Contact::findOrFail($id);
        return view('admin.contacts.show', compact('contact'));
    }

    /**
     * Gửi nội dung phản hồi liên hệ và cập nhật trạng thái đã hoàn thành (resolved).
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id ID của liên hệ cần phản hồi
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reply(Request $request, $id)
    {
        // 1. Xác thực nội dung phản hồi bắt buộc phải nhập
        $request->validate(['reply_message' => 'required|string']);
        
        $contact = Contact::findOrFail($id);
        
        // 2. Cập nhật câu trả lời phản hồi và chuyển trạng thái xử lý
        $contact->update([
            'reply_message' => $request->reply_message,
            'status' => 'resolved'
        ]);

        // Tính năng gửi mail thực tế (Tùy chọn cấu hình trong tương lai)
        // Mail::to($contact->email)->send(new \App\Mail\ContactReplyMail($contact));

        return redirect()->back()->with('success', 'Đã gửi phản hồi cho khách hàng thành công!');
    }
}
