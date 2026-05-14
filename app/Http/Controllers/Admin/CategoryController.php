<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
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

        return view('admin.categories.index', [
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
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:100'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        Category::query()->create([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'description' => $data['description'] ?? null,
            'icon' => $data['icon'] ?? 'fa-box',
            'sort_order' => $data['sort_order'] ?? 0,
            'is_active' => (bool) ($data['is_active'] ?? false),
        ]);

        return to_route('admin.categories.index')->with('status', 'Da tao danh muc moi.');
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:100'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $category->update([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'description' => $data['description'] ?? null,
            'icon' => $data['icon'] ?? 'fa-box',
            'sort_order' => $data['sort_order'] ?? 0,
            'is_active' => (bool) ($data['is_active'] ?? false),
        ]);

        return to_route('admin.categories.index')->with('status', 'Da cap nhat danh muc.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->products()->exists()) {
            return to_route('admin.categories.index')
                ->with('error', 'Khong the xoa danh muc dang co san pham.');
        }

        $category->delete();

        return to_route('admin.categories.index')->with('status', 'Da xoa danh muc.');
    }
}
