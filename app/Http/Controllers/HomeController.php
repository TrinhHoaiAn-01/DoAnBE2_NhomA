<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Sử dụng dữ liệu mẫu cho đồng bộ với tính năng tìm kiếm
        $category_groups = $this->getMockCategoryGroups();
        $allProducts = $this->getMockProducts();
        
        // Lấy danh sách Flash Sale (giảm giá mạnh)
        $flash_sales = collect($allProducts)->filter(fn($p) => ($p['old_price'] - $p['price']) > 2000000)->take(4);
        
        // Thời gian kết thúc flash sale (giả lập 2 tiếng nữa)
        $flash_sale_end = now()->addHours(2)->format('Y-m-d H:i:s');

        // Mock banners
        $banners = [
            ['image' => 'https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?q=80&w=1200', 'title' => 'Mega Sale - Giảm tới 50%', 'link' => '#'],
            ['image' => 'https://images.unsplash.com/photo-1607083206968-13611e3d76db?q=80&w=1200', 'title' => 'iPhone 15 Pro Max Mới', 'link' => '#'],
        ];

        return view('home', [
            'categories' => $categories,
            'category_groups' => $category_groups,
            'activeCategoryCount' => $activeCategoryCount,
            'featuredProductCount' => $featuredProductCount,
            'featured_products' => array_slice($allProducts, 0, 4),
            'flash_sales' => $flash_sales,
            'flash_sale_end' => $flash_sale_end,
            'banners' => $banners
        ]);
    }

    public function productList(Request $request)
    {
        $allProducts = $this->getMockProducts();
        $category_groups = $this->getMockCategoryGroups();

        // 1. Tìm kiếm theo từ khóa
        $search = $request->query('search');
        if (!empty($search)) {
            $allProducts = array_filter($allProducts, function($p) use ($search) {
                return stripos($p['name'], $search) !== false || stripos($p['description'], $search) !== false;
            });
        }

        // 2. Lọc theo danh mục
        $category = $request->query('category');
        if (!empty($category)) {
            $allProducts = array_filter($allProducts, function($p) use ($category) {
                return $p['category'] == $category || $p['group'] == $category;
            });
        }

        // 3. Lọc theo thương hiệu
        $brand = $request->query('brand');
        if (!empty($brand)) {
            $allProducts = array_filter($allProducts, function($p) use ($brand) {
                return isset($p['specs']['Thương hiệu']) && $p['specs']['Thương hiệu'] == $brand;
            });
        }

        // 4. Lọc theo giá
        $min_price = $request->query('min_price');
        $max_price = $request->query('max_price');
        if ($min_price !== null && $min_price !== '') {
            $allProducts = array_filter($allProducts, fn($p) => $p['price'] >= $min_price);
        }
        if ($max_price !== null && $max_price !== '') {
            $allProducts = array_filter($allProducts, fn($p) => $p['price'] <= $max_price);
        }

        // 5. Lọc theo khuyến mãi
        if ($request->query('on_sale')) {
            $allProducts = array_filter($allProducts, fn($p) => $p['old_price'] > $p['price']);
        }

        // Reset lại index sau khi filter
        $allProducts = array_values($allProducts);

        // 6. Lấy danh sách thương hiệu & danh mục để hiển thị ở bộ lọc
        $brands = array_unique(array_map(fn($p) => $p['specs']['Thương hiệu'] ?? 'Chính hãng', $this->getMockProducts()));
        $categories = [];
        foreach($category_groups as $group) {
            $categories[] = $group['name'];
            foreach($group['items'] as $item) {
                $categories[] = $item['name'];
            }
        }
        $categories = array_unique($categories);

        // Sắp xếp
        $sort = $request->query('sort', 'newest');
        if ($sort == 'price_asc') {
            usort($allProducts, fn($a, $b) => $a['price'] <=> $b['price']);
        } elseif ($sort == 'price_desc') {
            usort($allProducts, fn($a, $b) => $b['price'] <=> $a['price']);
        }

        // Phân trang
        $perPage = 12;
        $page = (int) $request->query('page', 1);
        $total = count($allProducts);
        $products = array_slice($allProducts, ($page - 1) * $perPage, $perPage);

        return view('product_list', compact('products', 'category_groups', 'total', 'perPage', 'page', 'brands', 'categories'));
    }

    public function showProduct($id)
    {
        $products = $this->getMockProducts();
        $product = collect($products)->firstWhere('id', $id) ?? abort(404);
        return view('product_detail', compact('product'));
    }

    private function getMockProducts()
    {
        $data = [
            ['id' => 1, 'name' => 'iPhone 15 Pro Max 256GB', 'price' => 29990000, 'category' => 'iPhone', 'group' => 'Thiết bị di động', 'tag' => 'Bán chạy', 'stock' => 15, 'rating' => 4.9, 'image' => 'https://images.unsplash.com/photo-1696446701796-da61225697cc?q=80&w=400'],
            ['id' => 2, 'name' => 'Samsung Galaxy S24 Ultra', 'price' => 26990000, 'category' => 'Samsung Galaxy', 'group' => 'Thiết bị di động', 'tag' => 'Mới về', 'stock' => 10, 'rating' => 4.8, 'image' => 'https://images.unsplash.com/photo-1610945265064-0e34e5519bbf?q=80&w=400'],
            ['id' => 3, 'name' => 'iPad Pro M2 11" 128GB', 'price' => 20490000, 'category' => 'Máy tính bảng', 'group' => 'Thiết bị di động', 'tag' => 'Hot', 'stock' => 5, 'rating' => 4.7, 'image' => 'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?q=80&w=400'],
            ['id' => 4, 'name' => 'Xiaomi 14 Ultra 5G', 'price' => 19990000, 'category' => 'Xiaomi & OPPO', 'group' => 'Thiết bị di động', 'tag' => 'Ưu đãi', 'stock' => 20, 'rating' => 4.6, 'image' => 'https://images.unsplash.com/photo-1598327105666-5b89351aff97?q=80&w=400'],
            ['id' => 5, 'name' => 'MacBook Pro 14" M3', 'price' => 39990000, 'category' => 'MacBook', 'group' => 'Máy tính & Laptop', 'tag' => 'Bán chạy', 'stock' => 7, 'rating' => 5.0, 'image' => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?q=80&w=400'],
            ['id' => 6, 'name' => 'ASUS ROG Strix G16', 'price' => 32490000, 'category' => 'Laptop Gaming', 'group' => 'Máy tính & Laptop', 'tag' => 'Mới về', 'stock' => 4, 'rating' => 4.8, 'image' => 'https://images.unsplash.com/photo-1603302576837-37561b2e2302?q=80&w=400'],
            ['id' => 7, 'name' => 'Dell XPS 13 Plus 9320', 'price' => 35990000, 'category' => 'Laptop', 'group' => 'Máy tính & Laptop', 'tag' => 'Hot', 'stock' => 3, 'rating' => 4.7, 'image' => 'https://images.unsplash.com/photo-1593642632823-8f785ba67e45?q=80&w=400'],
            ['id' => 8, 'name' => 'PC Gaming Neo G1', 'price' => 15990000, 'category' => 'PC Văn phòng', 'group' => 'Máy tính & Laptop', 'tag' => 'Ưu đãi', 'stock' => 12, 'rating' => 4.5, 'image' => 'https://images.unsplash.com/photo-1587831990711-23ca6441447b?q=80&w=400'],
            ['id' => 9, 'name' => 'AirPods Pro Gen 2', 'price' => 5990000, 'category' => 'Tai nghe', 'group' => 'Phụ kiện & Âm thanh', 'tag' => 'Bán chạy', 'stock' => 40, 'rating' => 4.9, 'image' => 'https://images.unsplash.com/photo-1588423770574-91993ca0a3f7?q=80&w=400'],
            ['id' => 10, 'name' => 'Sony WH-1000XM5', 'price' => 7490000, 'category' => 'Tai nghe Bluetooth', 'group' => 'Phụ kiện & Âm thanh', 'tag' => 'Hot', 'stock' => 0, 'rating' => 4.8, 'image' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?q=80&w=400'],
            ['id' => 11, 'name' => 'Marshall Emberton II', 'price' => 3990000, 'category' => 'Loa thông minh', 'group' => 'Phụ kiện & Âm thanh', 'tag' => 'Ưu đãi', 'stock' => 18, 'rating' => 4.7, 'image' => 'https://images.unsplash.com/photo-1589003077984-894e133dabab?q=80&w=400'],
            ['id' => 12, 'name' => 'Sạc dự phòng Anker 20k', 'price' => 1250000, 'category' => 'Cáp sạc & Pin dự phòng', 'group' => 'Phụ kiện & Âm thanh', 'tag' => 'Mới về', 'stock' => 100, 'rating' => 4.6, 'image' => 'https://images.unsplash.com/photo-1619130772021-9952932f9191?q=80&w=400'],
            ['id' => 13, 'name' => 'Apple Watch Ultra 2', 'price' => 21490000, 'category' => 'Apple Watch', 'group' => 'Thiết bị đeo', 'tag' => 'Bán chạy', 'stock' => 22, 'rating' => 5.0, 'image' => 'https://images.unsplash.com/photo-1434493907317-a46b53b81846?q=80&w=400'],
            ['id' => 14, 'name' => 'Garmin Fenix 7 Pro', 'price' => 18990000, 'category' => 'Đồng hồ thông minh khác', 'group' => 'Thiết bị đeo', 'tag' => 'Mới về', 'stock' => 6, 'rating' => 4.9, 'image' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?q=80&w=400'],
            ['id' => 15, 'name' => 'Xiaomi Band 8', 'price' => 890000, 'category' => 'Vòng đeo tay sức khỏe', 'group' => 'Thiết bị đeo', 'tag' => 'Ưu đãi', 'stock' => 150, 'rating' => 4.5, 'image' => 'https://images.unsplash.com/photo-1575311373937-040b8e3fd5b6?q=80&w=400'],
            ['id' => 16, 'name' => 'Apple Watch Series 9', 'price' => 10490000, 'category' => 'Apple Watch', 'group' => 'Thiết bị đeo', 'tag' => 'Hot', 'stock' => 14, 'rating' => 4.8, 'image' => 'https://images.unsplash.com/photo-1546868871-7041f2a55e12?q=80&w=400'],
            ['id' => 17, 'name' => 'Bàn phím cơ Keychron K2', 'price' => 1850000, 'category' => 'Phụ kiện', 'group' => 'Phụ kiện & Âm thanh', 'tag' => 'Mới về', 'stock' => 25, 'rating' => 4.7, 'image' => 'https://images.unsplash.com/photo-1511467687858-23d96c32e4ae?q=80&w=400'],
            ['id' => 18, 'name' => 'Chuột Logitech MX Master 3S', 'price' => 2450000, 'category' => 'Phụ kiện', 'group' => 'Phụ kiện & Âm thanh', 'tag' => 'Bán chạy', 'stock' => 30, 'rating' => 4.9, 'image' => 'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?q=80&w=400'],
            ['id' => 19, 'name' => 'Màn hình LG Gram +view', 'price' => 6500000, 'category' => 'Laptop', 'group' => 'Máy tính & Laptop', 'tag' => 'Ưu đãi', 'stock' => 10, 'rating' => 4.6, 'image' => 'https://images.unsplash.com/photo-1547119957-637f8679db1e?q=80&w=400'],
            ['id' => 20, 'name' => 'Tai nghe Beats Studio Pro', 'price' => 8490000, 'category' => 'Tai nghe', 'group' => 'Phụ kiện & Âm thanh', 'tag' => 'Hot', 'stock' => 5, 'rating' => 4.7, 'image' => 'https://images.unsplash.com/photo-1545127398-14699f92334b?q=80&w=400'],
        ];

        foreach ($data as &$p) {
            $p['reviews_count'] = rand(10, 200);
            $p['old_price'] = $p['price'] * 1.1;
            $p['description'] = "Mô tả chi tiết cho sản phẩm " . $p['name'] . ". Đây là dòng sản phẩm cao cấp với nhiều tính năng vượt trội.";
            $p['images'] = [$p['image'], $p['image']];
            $p['specs'] = ['Thương hiệu' => 'Chính hãng', 'Bảo hành' => '12 tháng'];
        }
        return $data;
    }

    private function getMockCategoryGroups()
    {
        return [
            [
                'name' => 'Thiết bị di động',
                'icon' => 'bi-phone',
                'image' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?q=80&w=400',
                'items' => [
                    ['name' => 'iPhone', 'count' => 120, 'icon' => 'bi-apple'],
                    ['name' => 'Samsung Galaxy', 'count' => 85, 'icon' => 'bi-android2'],
                    ['name' => 'Máy tính bảng', 'count' => 45, 'icon' => 'bi-tablet'],
                ]
            ],
            [
                'name' => 'Máy tính & Laptop',
                'icon' => 'bi-laptop',
                'image' => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?q=80&w=400',
                'items' => [
                    ['name' => 'MacBook', 'count' => 30, 'icon' => 'bi-command'],
                    ['name' => 'Laptop Gaming', 'count' => 55, 'icon' => 'bi-controller'],
                ]
            ],
            [
                'name' => 'Phụ kiện & Âm thanh',
                'icon' => 'bi-headphones',
                'image' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?q=80&w=400',
                'items' => [
                    ['name' => 'Tai nghe', 'count' => 90, 'icon' => 'bi-earbuds'],
                    ['name' => 'Phụ kiện', 'count' => 150, 'icon' => 'bi-usb-c'],
                ]
            ],
            [
                'name' => 'Thiết bị đeo',
                'icon' => 'bi-watch',
                'image' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?q=80&w=400',
                'items' => [
                    ['name' => 'Apple Watch', 'count' => 20, 'icon' => 'bi-watch'],
                    ['name' => 'Vòng đeo tay sức khỏe', 'count' => 15, 'icon' => 'bi-heart-pulse'],
                ]
            ]
        ];
    }
}
