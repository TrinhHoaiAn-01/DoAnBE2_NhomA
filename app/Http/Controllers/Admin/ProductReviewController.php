<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesCrudSafety;
use App\Http\Controllers\Controller;
use App\Models\ProductReview;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductReviewController extends Controller
{
    use HandlesCrudSafety;

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

    public function update(Request $request, ProductReview $review): RedirectResponse
    {
        $data = $this->validateCrud($request, [
            'is_approved' => ['required', 'boolean'],
        ]);

        return $this->runCrudOperation(function () use ($request, $review, $data): RedirectResponse {
            $this->transaction(function () use ($request, $review, $data): void {
                $lockedReview = $this->lockForCrud($review);
                $this->assertFreshRecord($request, $lockedReview, 'đánh giá');
                $lockedReview->update($data);
            });

            return to_route('admin.reviews.index')
                ->with('status', 'Đã cập nhật trạng thái đánh giá. Hệ thống đã kiểm tra phiên chỉnh sửa trước khi lưu.');
        }, 'cập nhật đánh giá');
    }

    public function destroy(ProductReview $review): RedirectResponse
    {
        return $this->runCrudOperation(function () use ($review): RedirectResponse {
            $this->transaction(function () use ($review): void {
                $lockedReview = $this->lockForCrud($review);
                $lockedReview->delete();
            });

            return to_route('admin.reviews.index')
                ->with('status', 'Đã xóa đánh giá. Hệ thống đã khóa bản ghi trong lúc xóa để tránh thao tác trùng.');
        }, 'xóa đánh giá');
    }
}
