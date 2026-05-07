<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $categories = Category::query()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('home', [
            'categories' => $categories,
            'activeCategoryCount' => $categories->where('is_active', true)->count(),
            'featuredProductCount' => Product::query()->where('is_active', true)->count(),
        ]);
    }
}
