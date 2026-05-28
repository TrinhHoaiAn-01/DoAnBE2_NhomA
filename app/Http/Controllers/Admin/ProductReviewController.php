<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductReview;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductReviewController extends Controller
{
    // Hien thi danh sach danh gia san pham.
    public function index(Request $request): View
    {
        $status = $request->string('status')->toString();

        $reviews = ProductReview::query()
            ->with('product')
            ->when($status === 'approved', fn ($query) => $query->where('is_approved', true))
            ->when($status === 'pending', fn ($query) => $query->where('is_approved', false))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.reviews', [
            'reviews' => $reviews,
            'status' => $status,
            'pendingCount' => ProductReview::query()->where('is_approved', false)->count(),
            'approvedCount' => ProductReview::query()->where('is_approved', true)->count(),
        ]);
    }

    // Duyet hoac tu choi danh gia san pham.
    public function update(Request $request, ProductReview $review): RedirectResponse
    {
        $data = $request->validate([
            'is_approved' => ['required', 'boolean'],
        ]);

        $review->update($data);

        return to_route('admin.reviews.index')->with('status', 'Đã cập nhật trạng thái đánh giá.');
    }

    // Xoa danh gia san pham.
    public function destroy(ProductReview $review): RedirectResponse
    {
        $review->delete();

        return to_route('admin.reviews.index')->with('status', 'Đã xóa đánh giá.');
    }
}
