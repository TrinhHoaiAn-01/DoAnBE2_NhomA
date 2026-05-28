<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controller ProductController
 *
 * Quản lý danh sách sản phẩm, tìm kiếm, lọc theo danh mục, sắp xếp, 
 * trang chi tiết sản phẩm, lịch sử sản phẩm đã xem, sản phẩm liên quan và gửi đánh giá (review).
 */
class ProductController extends Controller
{
    /**
     * Hiển thị danh sách sản phẩm có áp dụng bộ lọc tìm kiếm, danh mục và sắp xếp.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request): View
    {
        // 1. Lấy các tham số tìm kiếm, danh mục, sắp xếp từ Request
        $search = trim((string) $request->string('search'));
        $categorySlug = $request->string('category')->toString();
        $sort = $request->string('sort')->toString();

        // 2. Xây dựng câu truy vấn tìm kiếm sản phẩm đang hoạt động (is_active = true)
        $products = Product::query()
            ->with('category')
            ->where('is_active', true)
            ->when($search !== '', function ($query) use ($search): void {
                // Tìm kiếm theo tên sản phẩm, thương hiệu hoặc mô tả
                $query->where(function ($nested) use ($search): void {
                    $nested
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('brand', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($categorySlug !== '', function ($query) use ($categorySlug): void {
                // Lọc sản phẩm theo danh mục dựa trên slug
                $query->whereHas('category', fn($q) => $q->where('slug', $categorySlug));
            })
            // Sắp xếp theo các tiêu chí khác nhau
            ->when($sort === 'price_asc', fn($q) => $q->orderBy('price')) // Giá tăng dần
            ->when($sort === 'price_desc', fn($q) => $q->orderByDesc('price')) // Giá giảm dần
            ->when($sort === 'name', fn($q) => $q->orderBy('name')) // Theo tên A-Z
            ->when(!in_array($sort, ['price_asc', 'price_desc', 'name']), fn($q) => $q->orderByDesc('created_at')) // Mặc định: Mới nhất
            ->get();

        // 3. Lấy tất cả danh mục đang hoạt động kèm số lượng sản phẩm để hiển thị thanh bộ lọc danh mục
        $categories = Category::query()
            ->withCount(['products' => fn($q) => $q->where('is_active', true)])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        // Tìm danh mục hiện tại đang được lọc để hiển thị tiêu đề và thông tin danh mục
        $currentCategory = $categorySlug !== '' ? $categories->firstWhere('slug', $categorySlug) : null;

        return view('products.index', [
            'products' => $products,
            'categories' => $categories,
            'currentCategory' => $currentCategory,
            'search' => $search,
            'categorySlug' => $categorySlug,
            'sort' => $sort,
        ]);
    }

    /**
     * Hiển thị trang thông tin chi tiết một sản phẩm.
     * Cập nhật danh sách sản phẩm vừa xem của người dùng và gợi ý sản phẩm liên quan.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\View\View
     */
    public function show(Product $product): View
    {
        // 1. Lấy danh sách các đánh giá của sản phẩm đã được phê duyệt
        $approvedReviews = $product->reviews()
            ->where('is_approved', true)
            ->latest()
            ->get();

        // 2. Lấy lịch sử sản phẩm đã xem từ Session
        $recentlyViewedIds = session()->get('recently_viewed', []);

        // 3. Lọc ID sản phẩm vừa xem (loại bỏ sản phẩm hiện tại để không tự gợi ý chính nó)
        $displayIds = array_filter($recentlyViewedIds, fn($id) => $id !== $product->id);

        $recentlyViewedProducts = collect();
        if (!empty($displayIds)) {
            // Truy vấn thông tin của các sản phẩm đã xem trước đó
            $recentlyViewedProducts = Product::query()
                ->whereIn('id', $displayIds)
                ->where('is_active', true)
                ->get()
                // Đảm bảo thứ tự hiển thị đúng như thứ tự đã xem trong mảng Session
                ->sortBy(fn($item) => array_search($item->id, $displayIds))
                ->values();
        }

        // 4. Đưa sản phẩm hiện tại lên đầu danh sách sản phẩm đã xem
        $recentlyViewedIds = array_values(array_unique(array_merge([$product->id], $recentlyViewedIds)));
        // Giới hạn lưu lịch sử tối đa 5 sản phẩm gần nhất
        $recentlyViewedIds = array_slice($recentlyViewedIds, 0, 5);
        session()->put('recently_viewed', $recentlyViewedIds);

        return view('products.show', [
            'product' => $product->load('category'),
            'approvedReviews' => $approvedReviews,
            'averageRating' => round((float) $approvedReviews->avg('rating'), 1), // Điểm đánh giá trung bình
            'relatedProducts' => Product::query()
                // Gợi ý các sản phẩm cùng danh mục, ngoại trừ sản phẩm hiện tại
                ->where('category_id', $product->category_id)
                ->whereKeyNot($product->id)
                ->where('is_active', true)
                ->latest()
                ->limit(4)
                ->get(),
            'recentlyViewedProducts' => $recentlyViewedProducts, // Danh sách sản phẩm vừa xem
        ]);
    }

    /**
     * Xử lý gửi đánh giá sản phẩm của người dùng.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeReview(Request $request, Product $product): RedirectResponse
    {
        $reviewedProducts = $request->session()->get('reviewed_products', []);

        // 1. Kiểm tra xem người dùng đã đăng nhập và đã đánh giá sản phẩm này chưa
        if ($request->user() && ProductReview::query()->where('product_id', $product->id)->where('user_id', $request->user()->id)->exists()) {
            return back()->withInput()->with('error', 'Bạn đã gửi đánh giá cho sản phẩm này.');
        }

        // 2. Đối với khách vãng lai, kiểm tra lịch sử trong session của phiên hiện tại
        if (! $request->user() && in_array($product->id, $reviewedProducts, true)) {
            return back()->withInput()->with('error', 'Bạn đã gửi đánh giá cho sản phẩm này trong phiên hiện tại.');
        }

        // 3. Xác thực dữ liệu đánh giá
        $data = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'title' => ['nullable', 'string', 'max:255'],
            'content' => ['required', 'string', 'min:10', 'max:1000'],
        ]);

        // 4. Lưu đánh giá vào Database
        // Khách hàng đã đăng nhập thì tự động phê duyệt đánh giá (is_approved = true), khách vãng lai chờ duyệt (is_approved = false)
        ProductReview::query()->create($data + [
            'product_id' => $product->id,
            'user_id' => $request->user()?->id,
            'is_approved' => $request->user() !== null,
        ]);

        // Ghi nhận sản phẩm này đã được đánh giá trong session
        $request->session()->put('reviewed_products', array_values(array_unique([...$reviewedProducts, $product->id])));

        $message = $request->user()
            ? 'Đánh giá đã được đăng thành công.'
            : 'Đánh giá đã được gửi và đang chờ quản trị viên phê duyệt.';

        return to_route('products.show', $product)->with('status', $message);
    }
}

