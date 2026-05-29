<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesCrudSafety;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ProductController extends Controller
{
    use HandlesCrudSafety;

    public function index(Request $request): View
    {
        $search = trim((string) $request->string('search'));
        $categoryId = $request->integer('category_id');
        $stockStatus = $request->string('stock_status')->toString();

        return view('admin.products', [
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

        return $this->runCrudOperation(function () use ($request, $data): RedirectResponse {
            $data['image_url'] = $this->resolveProductImageUrl($request, $data);

            Product::query()->create($data + [
                'slug' => Str::slug($data['name']),
                'is_active' => (bool) ($data['is_active'] ?? false),
            ]);

            return to_route('admin.products.index')
                ->with('status', 'Đã thêm sản phẩm mới. Hệ thống đã kiểm tra SKU và đường dẫn để tránh trùng dữ liệu.');
        }, 'thêm sản phẩm');
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $this->validateProduct($request, $product);

        return $this->runCrudOperation(function () use ($request, $product, $data): RedirectResponse {
            $this->transaction(function () use ($request, $product, $data): void {
                $lockedProduct = $this->lockForCrud($product);
                $this->assertFreshRecord($request, $lockedProduct, 'sản phẩm');

                $data['image_url'] = $this->resolveProductImageUrl($request, $data, $lockedProduct);
                $lockedProduct->update($data + [
                    'slug' => Str::slug($data['name']),
                    'is_active' => (bool) ($data['is_active'] ?? false),
                ]);
            });

            return to_route('admin.products.index')
                ->with('status', 'Đã cập nhật sản phẩm. Hệ thống đã kiểm tra phiên chỉnh sửa trước khi lưu.');
        }, 'cập nhật sản phẩm');
    }

    public function destroy(Product $product): RedirectResponse
    {
        return $this->runCrudOperation(function () use ($product): RedirectResponse {
            $imageUrl = null;

            $this->transaction(function () use ($product, &$imageUrl): void {
                $lockedProduct = $this->lockForCrud($product);
                $imageUrl = $lockedProduct->image_url;
                $lockedProduct->delete();
            });

            $this->deleteStoredProductImage($imageUrl);

            return to_route('admin.products.index')
                ->with('status', 'Đã xóa sản phẩm. Hệ thống đã khóa bản ghi trong lúc xóa để tránh thao tác trùng.');
        }, 'xóa sản phẩm');
    }

    private function validateProduct(Request $request, ?Product $product = null): array
    {
        $data = $this->validateCrud($request, [
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'max:100', Rule::unique('products', 'sku')->ignore($product)],
            'brand' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'original_price' => ['nullable', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'image_url' => [
                'nullable',
                'string',
                'max:2048',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if (! $this->isValidProductImageUrl((string) $value)) {
                        $fail('Đường dẫn ảnh phải là URL hợp lệ hoặc đường dẫn ảnh đã tải lên.');
                    }
                },
            ],
            'product_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:4096'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'category_id' => 'danh mục sản phẩm',
            'name' => 'tên sản phẩm',
            'sku' => 'SKU sản phẩm',
        ]);

        $slug = Str::slug($data['name']);
        $slugExists = Product::query()
            ->where('slug', $slug)
            ->when($product, fn ($query) => $query->whereKeyNot($product->getKey()))
            ->exists();

        if ($slugExists) {
            throw ValidationException::withMessages([
                'name' => 'Tên sản phẩm tạo ra đường dẫn đã tồn tại. Vui lòng đổi tên để tránh hiển thị sai sản phẩm.',
            ]);
        }

        unset($data['product_image']);
        $data['image_url'] = $this->normalizeProductImageUrl($data['image_url'] ?? null);

        return $data;
    }

    private function resolveProductImageUrl(Request $request, array $data, ?Product $product = null): ?string
    {
        if ($request->hasFile('product_image')) {
            $path = $request->file('product_image')->store('products', 'public');

            if ($product) {
                $this->deleteStoredProductImage($product->image_url);
            }

            return '/storage/' . $path;
        }

        $imageUrl = $data['image_url'] ?? null;

        if ($product && $imageUrl !== $product->image_url) {
            $this->deleteStoredProductImage($product->image_url);
        }

        return $imageUrl;
    }

    private function normalizeProductImageUrl(mixed $imageUrl): ?string
    {
        if (blank($imageUrl)) {
            return null;
        }

        $imageUrl = trim((string) $imageUrl);

        if (Str::startsWith($imageUrl, ['storage/', 'uploads/'])) {
            return '/' . $imageUrl;
        }

        return $imageUrl;
    }

    private function deleteStoredProductImage(?string $imageUrl): void
    {
        if (blank($imageUrl)) {
            return;
        }

        $scheme = parse_url($imageUrl, PHP_URL_SCHEME);
        if (in_array($scheme, ['http', 'https'], true)) {
            $appHost = parse_url((string) config('app.url'), PHP_URL_HOST);
            $imageHost = parse_url($imageUrl, PHP_URL_HOST);

            if ($appHost && $imageHost && ! hash_equals($appHost, $imageHost)) {
                return;
            }
        }

        $path = parse_url($imageUrl, PHP_URL_PATH) ?: $imageUrl;
        $path = ltrim($path, '/');

        if (! Str::startsWith($path, 'storage/products/')) {
            return;
        }

        Storage::disk('public')->delete(Str::after($path, 'storage/'));
    }

    private function isValidProductImageUrl(string $imageUrl): bool
    {
        if (blank($imageUrl)) {
            return true;
        }

        $scheme = parse_url($imageUrl, PHP_URL_SCHEME);
        if (in_array($scheme, ['http', 'https'], true) && filter_var($imageUrl, FILTER_VALIDATE_URL)) {
            return true;
        }

        return Str::startsWith($imageUrl, [
            '/storage/',
            'storage/',
            '/uploads/',
            'uploads/',
        ]);
    }
}
