<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\View\View;

/**
 * Controller HomeController
 *
 * Xử lý yêu cầu hiển thị trang chủ của ứng dụng.
 * Tổng hợp dữ liệu về danh mục hoạt động, sản phẩm nổi bật, sản phẩm đề xuất, 
 * thông tin Flash Sale và các Banners quảng cáo.
 */
class HomeController extends Controller
{
    /**
     * Xử lý yêu cầu hiển thị trang chủ (Single-action Controller).
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function __invoke(): View
    {
        // Khởi tạo biến chứa sản phẩm đề xuất ban đầu là một collection rỗng
        $suggested_products = collect();

        // 1. Lấy danh sách danh mục đang hoạt động kèm theo số lượng sản phẩm đang mở bán của từng danh mục
        $categories = Category::query()
            ->withCount(['products' => fn($q) => $q->where('is_active', true)])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        // 2. Lấy danh sách sản phẩm nổi bật (Mới nhất, đang bán và còn hàng trong kho)
        $featured_products = Product::query()
            ->with('category')
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->latest()
            ->take(8)
            ->get();

        // 3. Đề xuất sản phẩm – lấy ngẫu nhiên 8 sản phẩm đang hoạt động và còn hàng
        $suggested_products = Product::query()
            ->with('category')
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->inRandomOrder()
            ->take(8)
            ->get();

        // 4. Lấy danh sách sản phẩm Flash Sale (Sản phẩm đang hoạt động và có giá bán hiện tại nhỏ hơn giá bán gốc)
        $flash_sales = Product::query()
            ->with('category')
            ->where('is_active', true)
            ->whereNotNull('original_price')
            ->whereColumn('price', '<', 'original_price')
            ->take(4)
            ->get();

        // 5. Thiết lập thời gian kết thúc flash sale (giả lập là 2 tiếng kể từ thời điểm hiện tại)
        $flash_sale_end = now()->addHours(2)->format('Y-m-d H:i:s');

        // 6. Mock dữ liệu Banners (Tạm thời mock dữ liệu tĩnh, sau này có thể cấu hình động từ Database)
        $banners = [
            [
                'image' => 'https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?q=80&w=1200', 
                'title' => 'Mega Sale - Giảm tới 50%', 
                'link' => route('products.index')
            ],
            [
                'image' => 'https://images.unsplash.com/photo-1607083206968-13611e3d76db?q=80&w=1200', 
                'title' => 'Sản phẩm mới nhất', 
                'link' => route('products.index')
            ],
        ];

        // 7. Trả về view 'home' với toàn bộ các biến dữ liệu cần thiết
        return view('home', [
            'categories' => $categories,
            'featured_products' => $featured_products,
            'flash_sales' => $flash_sales,
            'flash_sale_end' => $flash_sale_end,
            'suggested_products' => $suggested_products,
            'banners' => $banners,
        ]);
    }
}

