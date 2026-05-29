<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesCrudSafety;
use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FaqController extends Controller
{
    use HandlesCrudSafety;

    public function index()
    {
        $faqs = Faq::orderBy('sort_order', 'asc')->orderBy('created_at', 'desc')->get();

        return view('admin.faqs', compact('faqs'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateCrud($request, [
            'category' => ['required', 'string', 'max:255'],
            'question' => ['required', 'string', 'max:500', Rule::unique('faqs', 'question')],
            'answer' => ['required', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ], [
            'category' => 'danh mục FAQ',
            'question' => 'câu hỏi FAQ',
            'answer' => 'câu trả lời FAQ',
        ]);

        return $this->runCrudOperation(function () use ($data): RedirectResponse {
            Faq::create([
                'category' => $data['category'],
                'question' => $data['question'],
                'answer' => $data['answer'],
                'sort_order' => $data['sort_order'] ?? 0,
            ]);

            return redirect()->back()
                ->with('success', 'Đã thêm câu hỏi FAQ mới. Hệ thống đã kiểm tra để tránh câu hỏi trùng.');
        }, 'thêm FAQ');
    }

    public function toggle(Request $request, $id): RedirectResponse
    {
        $faq = Faq::find($id);

        if (! $faq) {
            return redirect()->back()
                ->with('error', 'FAQ này không còn tồn tại. Vui lòng tải lại danh sách trước khi thao tác.');
        }

        return $this->runCrudOperation(function () use ($request, $faq): RedirectResponse {
            $this->transaction(function () use ($request, $faq): void {
                $lockedFaq = $this->lockForCrud($faq);
                $this->assertFreshRecord($request, $lockedFaq, 'FAQ');
                $lockedFaq->update(['is_active' => ! $lockedFaq->is_active]);
            });

            return redirect()->back()
                ->with('success', 'Đã thay đổi trạng thái hiển thị FAQ. Hệ thống đã kiểm tra phiên chỉnh sửa trước khi lưu.');
        }, 'đổi trạng thái FAQ');
    }

    public function destroy($id): RedirectResponse
    {
        $faq = Faq::find($id);

        if (! $faq) {
            return redirect()->back()
                ->with('error', 'FAQ này đã được xóa trước đó. Danh sách đã được làm mới để tránh xóa nhầm.');
        }

        return $this->runCrudOperation(function () use ($faq): RedirectResponse {
            $this->transaction(function () use ($faq): void {
                $lockedFaq = $this->lockForCrud($faq);
                $lockedFaq->delete();
            });

            return redirect()->back()
                ->with('success', 'Đã xóa câu hỏi FAQ. Hệ thống đã khóa bản ghi trong lúc xóa để tránh thao tác trùng.');
        }, 'xóa FAQ');
    }
}
