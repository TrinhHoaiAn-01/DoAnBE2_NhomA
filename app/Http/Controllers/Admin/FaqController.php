<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

/**
 * Controller FaqController
 *
 * Quản lý danh sách các Câu hỏi thường gặp (FAQs - Frequently Asked Questions) của website.
 * Cung cấp các tính năng quản trị: Xem danh sách, thêm mới câu hỏi (phân nhóm chuyên mục),
 * thay đổi trạng thái hoạt động (bật/tắt hiển thị lên client) và xóa câu hỏi thường gặp.
 */
class FaqController extends Controller
{
    /**
     * Hiển thị danh sách toàn bộ các câu hỏi thường gặp.
     * Sắp xếp theo thứ tự ưu tiên (sort_order) tăng dần và ngày tạo giảm dần.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $faqs = Faq::orderBy('sort_order', 'asc')->orderBy('created_at', 'desc')->get();
        return view('admin.faqs', compact('faqs'));
    }

    /**
     * Xử lý thêm mới một câu hỏi thường gặp.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // 1. Xác thực dữ liệu câu hỏi FAQ
        $request->validate([
            'category' => 'required|string|max:255', // Chuyên mục (vd: Tài khoản, Thanh toán, Vận chuyển)
            'question' => 'required|string|max:500',
            'answer' => 'required|string',
            'sort_order' => 'nullable|integer'
        ]);

        // 2. Lưu câu hỏi mới vào Database
        Faq::create([
            'category' => $request->category,
            'question' => $request->question,
            'answer' => $request->answer,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->back()->with('success', 'Đã thêm câu hỏi FAQ mới!');
    }

    /**
     * Bật/tắt trạng thái ẩn hiển (Kích hoạt/Vô hiệu hóa) của câu hỏi thường gặp.
     *
     * @param int $id ID của câu hỏi
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggle($id)
    {
        $faq = Faq::findOrFail($id);
        
        // Đảo ngược trạng thái hoạt động của FAQ hiện tại
        $faq->update(['is_active' => !$faq->is_active]);
        
        return redirect()->back()->with('success', 'Đã thay đổi trạng thái hiển thị của FAQ!');
    }

    /**
     * Xóa câu hỏi thường gặp vĩnh viễn khỏi Database.
     *
     * @param int $id ID của câu hỏi cần xóa
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        Faq::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Đã xóa câu hỏi FAQ!');
    }
}

