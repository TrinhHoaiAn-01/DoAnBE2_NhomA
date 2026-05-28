<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

/**
 * Controller ProductController (Admin)
 *
 * Quản lý các Sản phẩm (Product) của hệ thống trong trang quản trị Admin.
 * Hỗ trợ các chức năng: Xem danh sách sản phẩm (lọc theo từ khóa, danh mục, trạng thái tồn kho),
 * thêm mới sản phẩm, cập nhật thông tin sản phẩm (tự động cập nhật slug), và xóa sản phẩm vĩnh viễn.
 */
class ProductController extends Controller
{
    /**
     * Hiển thị danh sách sản phẩm có áp dụng các bộ lọc nâng cao.
     * Thống kê tổng số lượng sản phẩm đang hoạt động, bị ẩn, và cảnh báo tồn kho.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request): View
    {
        // 1. Nhận các tham số lọc tìm kiếm và trạng thái tồn kho
        $search = trim((string) $request->string('search'));
        $categoryId = $request->integer('category_id');
        $stockStatus = $request->string('stock_status')->toString();

        return view('admin.products', [
            // 2. Truy vấn danh sách sản phẩm có liên kết danh mục
            'products' => Product::query()
                ->with('category')
                ->when($search !== '', function ($query) use ($search): void {
                    $query->where(function ($nested) use ($search): void {
                        // Tìm kiếm theo tên sản phẩm, mã SKU hoặc thương hiệu
                        $nested
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('sku', 'like', "%{$search}%")
                            ->orWhere('brand', 'like', "%{$search}%");
                    });
                })
                ->when($categoryId > 0, function ($query) use ($categoryId): void {
                    // Lọc theo danh mục sản phẩm
                    $query->where('category_id', $categoryId);
                })
                ->when($stockStatus === 'low', function ($query): void {
                    // Lọc sản phẩm sắp hết hàng (tồn kho từ 1 tới 10 sản phẩm)
                    $query->whereBetween('stock', [1, 10]);
                })
                ->when($stockStatus === 'out', function ($query): void {
                    // Lọc sản phẩm đã hết hàng trong kho (tồn kho = 0)
                    $query->where('stock', 0);
                })
                ->orderByDesc('created_at') // Sản phẩm mới nhất xếp lên đầu
                ->get(),
            
            // Lấy toàn bộ danh mục để hiển thị trong bộ lọc và form thêm mới/sửa
            'categories' => Category::query()->orderBy('sort_order')->get(),
            
            // Lấy thông tin sản phẩm đang được chọn sửa đổi (nếu có tham số ?product=id)
            'editing' => $request->filled('product')
                ? Product::query()->find($request->integer('product'))
                : null,
            'search' => $search,
            'categoryId' => $categoryId,
            'stockStatus' => $stockStatus,
            
            // Các chỉ số thống kê nhanh trên thanh điều khiển (Widget)
            'productCount' => Product::query()->count(),
            'lowStockCount' => Product::query()->where('stock', '<=', 10)->count(),
            'activeProductCount' => Product::query()->where('is_active', true)->count(),
            'hiddenProductCount' => Product::query()->where('is_active', false)->count(),
        ]);
    }

    /**
     * Xử lý thêm mới một sản phẩm.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Xác thực dữ liệu sản phẩm
        $data = $this->validateProduct($request);

        // 2. Tạo bản ghi sản phẩm mới kèm đường dẫn slug tự động sinh theo tên
        Product::query()->create($data + [
            'slug' => Str::slug($data['name']),
            'is_active' => (bool) ($data['is_active'] ?? false),
        ]);

        return to_route('admin.products.index')->with('status', 'Đã thêm sản phẩm mới.');
    }

    /**
     * Xử lý cập nhật thông tin sản phẩm.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        // 1. Xác thực dữ liệu (ngoại trừ mã SKU của chính sản phẩm đang cập nhật)
        $data = $this->validateProduct($request, $product);

        // 2. Tiến hành cập nhật thông tin và tạo lại slug đẹp
        $product->update($data + [
            'slug' => Str::slug($data['name']),
            'is_active' => (bool) ($data['is_active'] ?? false),
        ]);

        return to_route('admin.products.index')->with('status', 'Đã cập nhật sản phẩm.');
    }

    /**
     * Xử lý xóa sản phẩm vĩnh viễn khỏi Database.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return to_route('admin.products.index')->with('status', 'Đã xóa sản phẩm.');
    }

    /**
     * Phương thức nội bộ để xác thực thông tin đầu vào của sản phẩm.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product|null $product Đối tượng sản phẩm (nếu đang ở chế độ cập nhật)
     * @return array
     */
    private function validateProduct(Request $request, ?Product $product = null): array
    {
        return $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            // Mã SKU phải là duy nhất (ngoại trừ SKU của sản phẩm hiện tại nếu đang edit)
            'sku' => ['required', 'string', 'max:100', 'unique:products,sku'.($product ? ','.$product->id : '')],
            'brand' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'original_price' => ['nullable', 'numeric', 'min:0'], // Giá gốc ban đầu (nếu có để phục vụ Flash Sale)
            'stock' => ['required', 'integer', 'min:0'],
            'image_url' => ['nullable', 'url'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }
}

