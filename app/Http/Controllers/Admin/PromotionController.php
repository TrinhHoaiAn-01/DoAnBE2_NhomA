<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PromotionController extends Controller
{
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

        return view('admin.promotions.index', [
            'promotions' => $promotions,
            'editing' => $request->filled('promotion')
                ? Promotion::query()->find($request->integer('promotion'))
                : null,
            'search' => $search,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Promotion::query()->create($this->validatedData($request));

        return to_route('admin.promotions.index')->with('status', 'Đã tạo mã giảm giá mới.');
    }

    public function update(Request $request, Promotion $promotion): RedirectResponse
    {
        $promotion->update($this->validatedData($request, $promotion));

        return to_route('admin.promotions.index')->with('status', 'Đã cập nhật mã giảm giá.');
    }

    public function destroy(Promotion $promotion): RedirectResponse
    {
        $promotion->delete();

        return to_route('admin.promotions.index')->with('status', 'Đã xóa mã giảm giá.');
    }

    private function validatedData(Request $request, ?Promotion $promotion = null): array
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:40', Rule::unique('promotions')->ignore($promotion)],
            'name' => ['required', 'string', 'max:255'],
            'discount_type' => ['required', 'in:fixed,percent'],
            'discount_value' => ['required', 'numeric', 'min:1'],
            'minimum_order' => ['nullable', 'numeric', 'min:0'],
            'usage_limit' => ['nullable', 'integer', 'min:1'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['code'] = mb_strtoupper($data['code']);
        $data['minimum_order'] = $data['minimum_order'] ?? 0;
        $data['is_active'] = (bool) ($data['is_active'] ?? false);

        return $data;
    }
}
