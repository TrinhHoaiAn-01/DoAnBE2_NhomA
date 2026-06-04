<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesCrudSafety;
use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PromotionController extends Controller
{
    use HandlesCrudSafety;

    public function index(Request $request): View
    {
        $search = trim((string) $request->string('search'));

        $promotions = Promotion::query()
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($nested) use ($search): void {
                    $nested
                        ->where('code', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.promotions', [
            'promotions' => $promotions,
            'editing' => $request->filled('promotion')
                ? Promotion::query()->find($request->integer('promotion'))
                : null,
            'search' => $search,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);

        return $this->runCrudOperation(function () use ($data): RedirectResponse {
            Promotion::query()->create($data);

            return to_route('admin.promotions.index')
                ->with('status', 'Đã tạo mã giảm giá mới. Mã đã được chuẩn hóa chữ hoa để tránh trùng lặp.');
        }, 'tạo mã giảm giá');
    }

    public function update(Request $request, Promotion $promotion): RedirectResponse
    {
        $data = $this->validatedData($request, $promotion);

        return $this->runCrudOperation(function () use ($request, $promotion, $data): RedirectResponse {
            $this->transaction(function () use ($request, $promotion, $data): void {
                $lockedPromotion = $this->lockForCrud($promotion);
                $this->assertFreshRecord($request, $lockedPromotion, 'mã giảm giá');
                $lockedPromotion->update($data);
            });

            return to_route('admin.promotions.index')
                ->with('status', 'Đã cập nhật mã giảm giá. Hệ thống đã kiểm tra phiên chỉnh sửa trước khi lưu.');
        }, 'cập nhật mã giảm giá');
    }

    public function destroy(Promotion $promotion): RedirectResponse
    {
        return $this->runCrudOperation(function () use ($promotion): RedirectResponse {
            $this->transaction(function () use ($promotion): void {
                $lockedPromotion = $this->lockForCrud($promotion);
                $lockedPromotion->delete();
            });

            return to_route('admin.promotions.index')
                ->with('status', 'Đã xóa mã giảm giá. Hệ thống đã khóa bản ghi trong lúc xóa để tránh thao tác trùng.');
        }, 'xóa mã giảm giá');
    }

    private function validatedData(Request $request, ?Promotion $promotion = null): array
    {
        if ($request->filled('code')) {
            $request->merge(['code' => mb_strtoupper(trim((string) $request->input('code')))]);
        }

        $data = $this->validateCrud($request, [
            'code' => ['required', 'string', 'max:40', Rule::unique('promotions', 'code')->ignore($promotion)],
            'name' => ['required', 'string', 'max:255'],
            'discount_type' => ['required', 'in:fixed,percent'],
            'discount_value' => ['required', 'numeric', 'min:1'],
            'minimum_order' => ['nullable', 'numeric', 'min:0'],
            'usage_limit' => ['nullable', 'integer', 'min:1'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'code' => 'mã giảm giá',
            'name' => 'tên chương trình',
        ]);

        if ($data['discount_type'] === 'percent' && (float) $data['discount_value'] > 100) {
            throw ValidationException::withMessages([
                'discount_value' => 'Phần trăm giảm giá không được vượt quá 100%. Vui lòng nhập lại để tránh lưu dữ liệu sai.',
            ]);
        }

        $data['minimum_order'] = $data['minimum_order'] ?? 0;
        $data['is_active'] = (bool) ($data['is_active'] ?? false);

        return $data;
    }
}
