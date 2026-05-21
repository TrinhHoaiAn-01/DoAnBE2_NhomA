<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->string('search'));
        $categorySlug = $request->string('category')->toString();
        $sort = $request->string('sort')->toString();

        $products = Product::query()
            ->with('category')
            ->where('is_active', true)
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($nested) use ($search): void {
                    $nested
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('brand', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($categorySlug !== '', function ($query) use ($categorySlug): void {
                $query->whereHas('category', fn($q) => $q->where('slug', $categorySlug));
            })
            ->when($sort === 'price_asc', fn($q) => $q->orderBy('price'))
            ->when($sort === 'price_desc', fn($q) => $q->orderByDesc('price'))
            ->when($sort === 'name', fn($q) => $q->orderBy('name'))
            ->when(!in_array($sort, ['price_asc', 'price_desc', 'name']), fn($q) => $q->orderByDesc('created_at'))
            ->get();

        $categories = Category::query()
            ->withCount(['products' => fn($q) => $q->where('is_active', true)])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        // Tìm danh mục hiện tại đang lọc (nếu có)
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

    public function show(Product $product): View
    {
        $approvedReviews = $product->reviews()
            ->where('is_approved', true)
            ->latest()
            ->get();

        return view('products.show', [
            'product' => $product->load('category'),
            'approvedReviews' => $approvedReviews,
            'averageRating' => round((float) $approvedReviews->avg('rating'), 1),
            'relatedProducts' => Product::query()
                ->where('category_id', $product->category_id)
                ->whereKeyNot($product->id)
                ->where('is_active', true)
                ->latest()
                ->limit(4)
                ->get(),
        ]);
    }

    public function storeReview(Request $request, Product $product): RedirectResponse
    {
        $reviewedProducts = $request->session()->get('reviewed_products', []);

        if ($request->user() && ProductReview::query()->where('product_id', $product->id)->where('user_id', $request->user()->id)->exists()) {
            return back()->withInput()->with('error', 'Ban da gui danh gia cho san pham nay.');
        }

        if (! $request->user() && in_array($product->id, $reviewedProducts, true)) {
            return back()->withInput()->with('error', 'Ban da gui danh gia cho san pham nay trong phien hien tai.');
        }

        $data = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'title' => ['nullable', 'string', 'max:255'],
            'content' => ['required', 'string', 'min:10', 'max:1000'],
        ]);

        ProductReview::query()->create($data + [
            'product_id' => $product->id,
            'user_id' => $request->user()?->id,
            'is_approved' => $request->user() !== null,
        ]);

        $request->session()->put('reviewed_products', array_values(array_unique([...$reviewedProducts, $product->id])));

        $message = $request->user()
            ? 'Danh gia da duoc dang.'
            : 'Danh gia da duoc gui va dang cho duyet.';

        return to_route('products.show', $product)->with('status', $message);
    }
}
