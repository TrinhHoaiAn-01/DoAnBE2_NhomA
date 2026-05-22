<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    // Hiển thị danh sách câu hỏi thường gặp (FAQ)
    public function index()
    {
        $faqs = Faq::orderBy('sort_order', 'asc')->orderBy('created_at', 'desc')->get();
        return view('admin.faqs', compact('faqs'));
    }

    // Thêm mới câu hỏi thường gặp
    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|string|max:255',
            'question' => 'required|string|max:500',
            'answer' => 'required|string',
            'sort_order' => 'nullable|integer'
        ]);

        Faq::create([
            'category' => $request->category,
            'question' => $request->question,
            'answer' => $request->answer,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->back()->with('success', 'Đã thêm câu hỏi FAQ mới!');
    }

    // Bật/tắt trạng thái ẩn/hiển thị của câu hỏi
    public function toggle($id)
    {
        $faq = Faq::findOrFail($id);
        $faq->update(['is_active' => !$faq->is_active]);
        return redirect()->back()->with('success', 'Đã thay đổi trạng thái hiển thị của FAQ!');
    }

    // Xóa câu hỏi thường gặp
    public function destroy($id)
    {
        Faq::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Đã xóa câu hỏi FAQ!');
    }
}
