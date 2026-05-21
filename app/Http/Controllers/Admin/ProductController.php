<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->string('search'));
        $categoryId = $request->integer('category_id');
        $stockStatus = $request->string('stock_status')->toString();

        return view('admin.products.index', [
            'products' => Product::query()
                ->with('category')
                ->when($search !== '', function ($query) use ($search): void {
                    $query->where(function ($nested) use ($search): void {
                        $nested
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('sku', 'like', "%{$search}%")
                            ->orWhere('brand', 'like', "%{$search}%");
                    });
                })
                ->when($categoryId > 0, function ($query) use ($categoryId): void {
                    $query->where('category_id', $categoryId);
                })
                ->when($stockStatus === 'low', function ($query): void {
                    $query->whereBetween('stock', [1, 10]);
                })
                ->when($stockStatus === 'out', function ($query): void {
                    $query->where('stock', 0);
                })
                ->orderByDesc('created_at')
                ->get(),
            'categories' => Category::query()->orderBy('sort_order')->get(),
            'editing' => $request->filled('product')
                ? Product::query()->find($request->integer('product'))
                : null,
            'search' => $search,
            'categoryId' => $categoryId,
            'stockStatus' => $stockStatus,
            'productCount' => Product::query()->count(),
            'lowStockCount' => Product::query()->where('stock', '<=', 10)->count(),
            'activeProductCount' => Product::query()->where('is_active', true)->count(),
            'hiddenProductCount' => Product::query()->where('is_active', false)->count(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateProduct($request);

        Product::query()->create($data + [
            'slug' => Str::slug($data['name']),
            'is_active' => (bool) ($data['is_active'] ?? false),
        ]);

        return to_route('admin.products.index')->with('status', 'Đã thêm sản phẩm mới.');
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $this->validateProduct($request, $product);

        $product->update($data + [
            'slug' => Str::slug($data['name']),
            'is_active' => (bool) ($data['is_active'] ?? false),
        ]);

        return to_route('admin.products.index')->with('status', 'Đã cập nhật sản phẩm.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return to_route('admin.products.index')->with('status', 'Đã xóa sản phẩm.');
    }

    private function validateProduct(Request $request, ?Product $product = null): array
    {
        return $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'max:100', 'unique:products,sku'.($product ? ','.$product->id : '')],
            'brand' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'original_price' => ['nullable', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'image_url' => ['nullable', 'url'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }
}
