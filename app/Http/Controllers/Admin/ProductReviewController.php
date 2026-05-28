<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductReview;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controller ProductReviewController (Admin)
 *
 * Quản lý các đánh giá (Review/Comment) sản phẩm của người dùng gửi lên.
 * Hỗ trợ các tính năng quản trị: Xem danh sách đánh giá (phân trang, lọc theo trạng thái duyệt),
 * phê duyệt (approve) hoặc từ chối ẩn đánh giá, và xóa đánh giá vĩnh viễn khỏi Database.
 */
class ProductReviewController extends Controller
{
    /**
     * Hiển thị danh sách các đánh giá sản phẩm kèm bộ lọc trạng thái.
     * Thống kê số lượng đánh giá chờ duyệt và đã duyệt.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request): View
    {
        // Lấy bộ lọc trạng thái duyệt từ query string (approved / pending)
        $status = $request->string('status')->toString();

        // 1. Truy vấn danh sách đánh giá kèm thông tin sản phẩm liên kết
        $reviews = ProductReview::query()
            ->with('product')
            ->when($status === 'approved', fn ($query) => $query->where('is_approved', true)) // Đã duyệt
            ->when($status === 'pending', fn ($query) => $query->where('is_approved', false))  // Chờ duyệt
            ->latest() // Đánh giá mới nhất đưa lên trước
            ->paginate(12) // Phân trang 12 đánh giá trên mỗi trang
            ->withQueryString();

        return view('admin.reviews', [
            'reviews' => $reviews,
            'status' => $status,
            // Thống kê số lượng phục vụ hiển thị nhãn số lượng trên các tab lọc
            'pendingCount' => ProductReview::query()->where('is_approved', false)->count(),
            'approvedCount' => ProductReview::query()->where('is_approved', true)->count(),
        ]);
    }

    /**
     * Duyệt hoặc hủy duyệt (ẩn hiển thị) một đánh giá sản phẩm.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\ProductReview $review
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, ProductReview $review): RedirectResponse
    {
        // 1. Xác thực trạng thái duyệt gửi lên (true / false)
        $data = $request->validate([
            'is_approved' => ['required', 'boolean'],
        ]);

        // 2. Cập nhật vào cơ sở dữ liệu
        $review->update($data);

        return to_route('admin.reviews.index')->with('status', 'Đã cập nhật trạng thái đánh giá.');
    }

    /**
     * Xóa vĩnh viễn một đánh giá sản phẩm khỏi hệ thống.
     *
     * @param \App\Models\ProductReview $review
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(ProductReview $review): RedirectResponse
    {
        $review->delete();

        return to_route('admin.reviews.index')->with('status', 'Đã xóa đánh giá.');
    }
}

