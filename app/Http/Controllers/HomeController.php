<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        // Danh mục đang hoạt động (kèm số lượng sản phẩm)
        $categories = Category::query()
            ->withCount(['products' => fn($q) => $q->where('is_active', true)])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        // Sản phẩm nổi bật (mới nhất, đang bán)
        $featured_products = Product::query()
            ->with('category')
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->latest()
            ->take(8)
            ->get();

        // Flash Sale: sản phẩm có giá gốc cao hơn giá bán (đang giảm giá)
        $flash_sales = Product::query()
            ->with('category')
            ->where('is_active', true)
            ->whereNotNull('original_price')
            ->whereColumn('price', '<', 'original_price')
            ->take(4)
            ->get();

        // Thời gian kết thúc flash sale (giả lập 2 tiếng nữa)
        $flash_sale_end = now()->addHours(2)->format('Y-m-d H:i:s');

        // Banners (tạm dùng mock — sau này có thể đọc từ bảng banners)
        $banners = [
            ['image' => 'https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?q=80&w=1200', 'title' => 'Mega Sale - Giảm tới 50%', 'link' => route('products.index')],
            ['image' => 'https://images.unsplash.com/photo-1607083206968-13611e3d76db?q=80&w=1200', 'title' => 'Sản phẩm mới nhất', 'link' => route('products.index')],
        ];

        return view('home', [
            'categories' => $categories,
            'featured_products' => $featured_products,
            'flash_sales' => $flash_sales,
            'flash_sale_end' => $flash_sale_end,
            'banners' => $banners,
        ]);
    }
}
