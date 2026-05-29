<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesCrudSafety;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CategoryController extends Controller
{
    use HandlesCrudSafety;

    public function index(Request $request): View
    {
        $search = trim((string) $request->string('search'));
        $status = $request->string('status')->toString();

        $categories = Category::query()
            ->withCount('products')
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($nested) use ($search): void {
                    $nested
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%");
                });
            })
            ->when($status !== '', function ($query) use ($status): void {
                $query->where('is_active', $status === 'active');
            })
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('admin.categories', [
            'categories' => $categories,
            'editing' => $request->filled('category')
                ? Category::query()->find($request->integer('category'))
                : null,
            'search' => $search,
            'status' => $status,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedCategory($request);

        return $this->runCrudOperation(function () use ($data): RedirectResponse {
            Category::query()->create($data);

            return to_route('admin.categories.index')
                ->with('status', 'Đã tạo danh mục mới. Dữ liệu đã được kiểm tra để tránh trùng lặp.');
        }, 'tạo danh mục');
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $data = $this->validatedCategory($request, $category);

        return $this->runCrudOperation(function () use ($request, $category, $data): RedirectResponse {
            $this->transaction(function () use ($request, $category, $data): void {
                $lockedCategory = $this->lockForCrud($category);
                $this->assertFreshRecord($request, $lockedCategory, 'danh mục');
                $lockedCategory->update($data);
            });

            return to_route('admin.categories.index')
                ->with('status', 'Đã cập nhật danh mục. Hệ thống đã kiểm tra phiên chỉnh sửa trước khi lưu.');
        }, 'cập nhật danh mục');
    }

    public function destroy(Category $category): RedirectResponse
    {
        return $this->runCrudOperation(function () use ($category): RedirectResponse {
            $this->transaction(function () use ($category): void {
                $lockedCategory = $this->lockForCrud($category);

                if ($lockedCategory->products()->exists()) {
                    throw ValidationException::withMessages([
                        'delete' => 'Không thể xóa danh mục đang có sản phẩm. Vui lòng chuyển hoặc xóa sản phẩm liên quan trước.',
                    ]);
                }

                $lockedCategory->delete();
            });

            return to_route('admin.categories.index')
                ->with('status', 'Đã xóa danh mục. Hệ thống đã kiểm tra liên kết trước khi xóa.');
        }, 'xóa danh mục');
    }

    private function validatedCategory(Request $request, ?Category $category = null): array
    {
        $data = $this->validateCrud($request, [
            'name' => ['required', 'string', 'max:255', Rule::unique('categories', 'name')->ignore($category)],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:100'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'name' => 'tên danh mục',
        ]);

        $slug = Str::slug($data['name']);
        $slugExists = Category::query()
            ->where('slug', $slug)
            ->when($category, fn ($query) => $query->whereKeyNot($category->getKey()))
            ->exists();

        if ($slugExists) {
            throw ValidationException::withMessages([
                'name' => 'Tên danh mục tạo ra đường dẫn đã tồn tại. Vui lòng đổi tên để tránh bản ghi trùng.',
            ]);
        }

        return [
            'name' => $data['name'],
            'slug' => $slug,
            'description' => $data['description'] ?? null,
            'icon' => $data['icon'] ?? 'fa-box',
            'sort_order' => $data['sort_order'] ?? 0,
            'is_active' => (bool) ($data['is_active'] ?? false),
        ];
    }
}
