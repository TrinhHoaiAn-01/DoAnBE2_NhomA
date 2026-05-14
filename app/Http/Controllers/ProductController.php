<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        return view('products.index', [
            'products' => Product::query()
                ->with('category')
                ->where('is_active', true)
                ->orderByDesc('created_at')
                ->get(),
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
