<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

/**
 * Controller CategoryController
 *
 * Quản lý danh mục sản phẩm (Category) trong hệ thống admin.
 * Hỗ trợ các chức năng: Xem danh sách danh mục (phân loại lọc tìm kiếm), 
 * thêm mới, cập nhật thông tin (sinh slug tự động) và xóa danh mục (chỉ cho phép xóa nếu danh mục không chứa sản phẩm).
 */
class CategoryController extends Controller
{
    /**
     * Hiển thị danh sách danh mục sản phẩm có áp dụng bộ lọc tìm kiếm và trạng thái.
     * Hỗ trợ lấy thông tin danh mục đang được chọn sửa đổi (edit mode).
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request): View
    {
        // 1. Nhận các tham số lọc tìm kiếm và trạng thái ẩn hiện
        $search = trim((string) $request->string('search'));
        $status = $request->string('status')->toString();

        // 2. Thực hiện truy vấn danh sách danh mục sản phẩm
        $categories = Category::query()
            ->withCount('products') // Đếm số lượng sản phẩm thuộc danh mục
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($nested) use ($search): void {
                    // Tìm theo tên hoặc đường dẫn slug
                    $nested
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%");
                });
            })
            ->when($status !== '', function ($query) use ($status): void {
                // Lọc theo trạng thái hoạt động (active / inactive)
                $query->where('is_active', $status === 'active');
            })
            ->orderBy('sort_order') // Sắp xếp theo thứ tự ưu tiên
            ->orderBy('name')
            ->get();

        return view('admin.categories', [
            'categories' => $categories,
            // Nếu có tham số category trên URL, lấy thông tin danh mục để đưa vào Form sửa
            'editing' => $request->filled('category')
                ? Category::query()->find($request->integer('category'))
                : null,
            'search' => $search,
            'status' => $status,
        ]);
    }

    /**
     * Xử lý thêm mới một danh mục sản phẩm.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Xác thực dữ liệu danh mục mới
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:100'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        // 2. Lưu vào Database và tự động tạo đường dẫn slug đẹp từ Tên danh mục
        Category::query()->create([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'description' => $data['description'] ?? null,
            'icon' => $data['icon'] ?? 'fa-box',
            'sort_order' => $data['sort_order'] ?? 0,
            'is_active' => (bool) ($data['is_active'] ?? false),
        ]);

        return to_route('admin.categories.index')->with('status', 'Đã tạo danh mục mới.');
    }

    /**
     * Xử lý cập nhật thông tin một danh mục sản phẩm.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Category $category): RedirectResponse
    {
        // 1. Xác thực dữ liệu cập nhật
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:100'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        // 2. Cập nhật dữ liệu danh mục và tạo lại slug theo tên mới
        $category->update([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'description' => $data['description'] ?? null,
            'icon' => $data['icon'] ?? 'fa-box',
            'sort_order' => $data['sort_order'] ?? 0,
            'is_active' => (bool) ($data['is_active'] ?? false),
        ]);

        return to_route('admin.categories.index')->with('status', 'Đã cập nhật danh mục.');
    }

    /**
     * Xử lý xóa một danh mục sản phẩm vĩnh viễn khỏi hệ thống.
     * Ràng buộc: Chặn xóa danh mục nếu danh mục đang chứa bất kỳ sản phẩm nào.
     *
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Category $category): RedirectResponse
    {
        // Kiểm tra xem danh mục này có chứa sản phẩm nào không
        if ($category->products()->exists()) {
            return to_route('admin.categories.index')
                ->with('error', 'Không thể xóa danh mục đang có sản phẩm.');
        }

        // Thực hiện xóa nếu hợp lệ
        $category->delete();

        return to_route('admin.categories.index')->with('status', 'Đã xóa danh mục.');
    }
}

