<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Controller PromotionController (Admin)
 *
 * Quản lý các chương trình khuyến mãi và mã giảm giá (Promotion/Coupon) trong hệ thống admin.
 * Hỗ trợ các tính năng: Xem danh sách mã giảm giá (tìm kiếm theo mã/tên), thêm mới, cập nhật, 
 * xóa và kiểm tra tính hợp lệ của dữ liệu khuyến mãi (loại giảm giá, giá trị tối đa 100% nếu là phần trăm).
 */
class PromotionController extends Controller
{
    /**
     * Hiển thị danh sách mã giảm giá và hỗ trợ chế độ chỉnh sửa (editing mode).
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request): View
    {
        // Lấy từ khóa tìm kiếm từ Request
        $search = trim((string) $request->string('search'));

        // 1. Truy vấn danh sách các mã giảm giá
        $promotions = Promotion::query()
            ->when($search !== '', function ($query) use ($search): void {
                // Lọc theo mã code hoặc tên chương trình khuyến mãi
                $query->where(function ($nested) use ($search): void {
                    $nested
                        ->where('code', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%");
                });
            })
            ->latest() // Mã mới tạo lên đầu
            ->paginate(12) // Phân trang 12 bản ghi trên mỗi trang
            ->withQueryString();

        return view('admin.promotions', [
            'promotions' => $promotions,
            // Lấy thông tin mã giảm giá đang sửa đổi nếu có tham số ?promotion=id
            'editing' => $request->filled('promotion')
                ? Promotion::query()->find($request->integer('promotion'))
                : null,
            'search' => $search,
        ]);
    }

    /**
     * Xử lý thêm mới một mã giảm giá.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        // Xác thực dữ liệu và tạo bản ghi khuyến mãi mới
        Promotion::query()->create($this->validatedData($request));

        return to_route('admin.promotions.index')->with('status', 'Đã tạo mã giảm giá mới.');
    }

    /**
     * Xử lý cập nhật thông tin mã giảm giá.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Promotion $promotion
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Promotion $promotion): RedirectResponse
    {
        // Xác thực dữ liệu và tiến hành cập nhật bản ghi
        $promotion->update($this->validatedData($request, $promotion));

        return to_route('admin.promotions.index')->with('status', 'Đã cập nhật mã giảm giá.');
    }

    /**
     * Xóa vĩnh viễn một mã giảm giá khỏi hệ thống.
     *
     * @param \App\Models\Promotion $promotion
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Promotion $promotion): RedirectResponse
    {
        $promotion->delete();

        return to_route('admin.promotions.index')->with('status', 'Đã xóa mã giảm giá.');
    }

    /**
     * Phương thức nội bộ xác thực dữ liệu mã giảm giá và chuẩn hóa dữ liệu.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Promotion|null $promotion Đối tượng khuyến mãi (nếu đang ở chế độ cập nhật)
     * @return array Dữ liệu đã được xác thực và chuẩn hóa
     * @throws \Illuminate\Validation\ValidationException
     */
    private function validatedData(Request $request, ?Promotion $promotion = null): array
    {
        // 1. Xác thực các trường cơ bản
        $data = $request->validate([
            'code' => ['required', 'string', 'max:40', Rule::unique('promotions')->ignore($promotion)], // Mã code là duy nhất
            'name' => ['required', 'string', 'max:255'],
            'discount_type' => ['required', 'in:fixed,percent'], // Giảm giá theo số tiền cố định hoặc phần trăm
            'discount_value' => ['required', 'numeric', 'min:1'],
            'minimum_order' => ['nullable', 'numeric', 'min:0'], // Giá trị đơn hàng tối thiểu
            'usage_limit' => ['nullable', 'integer', 'min:1'], // Giới hạn lượt sử dụng tối đa
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'], // Ngày kết thúc phải sau hoặc bằng ngày bắt đầu
            'is_active' => ['nullable', 'boolean'],
        ]);

        // 2. Chuẩn hóa mã giảm giá viết hoa toàn bộ để tránh lỗi phân biệt chữ hoa chữ thường
        $data['code'] = mb_strtoupper($data['code']);
        
        // Ràng buộc nghiệp vụ: Nếu giảm theo phần trăm (percent), giá trị không được vượt quá 100%
        if ($data['discount_type'] === 'percent' && (float) $data['discount_value'] > 100) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'discount_value' => 'Phần trăm giảm giá không được vượt quá 100%.',
            ]);
        }

        $data['minimum_order'] = $data['minimum_order'] ?? 0;
        $data['is_active'] = (bool) ($data['is_active'] ?? false);

        return $data;
    }
}

