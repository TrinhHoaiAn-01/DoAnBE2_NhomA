<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Tests\TestCase;

class RecentlyViewedProductTest extends TestCase
{
    use DatabaseTransactions;

    public function test_recently_viewed_products_are_stored_in_session(): void
    {
        $suffix = Str::lower(Str::random(8));

        // Tao du lieu rieng cho moi lan chay de khong trung slug trong database test.
        $category = Category::query()->create([
            'name' => 'Test Category',
            'slug' => 'test-category-'.$suffix,
            'is_active' => true,
        ]);

        // Tao hai san pham de kiem tra thu tu da xem gan day.
        $product1 = Product::query()->create([
            'category_id' => $category->id,
            'name' => 'Product 1',
            'slug' => 'product-1-'.$suffix,
            'sku' => 'PROD1-'.$suffix,
            'price' => 100000,
            'stock' => 10,
            'is_active' => true,
        ]);

        $product2 = Product::query()->create([
            'category_id' => $category->id,
            'name' => 'Product 2',
            'slug' => 'product-2-'.$suffix,
            'sku' => 'PROD2-'.$suffix,
            'price' => 200000,
            'stock' => 5,
            'is_active' => true,
        ]);

        // Xem chi tiet san pham thu nhat.
        $response = $this->get(route('products.show', $product1));
        $response->assertStatus(200);

        // Session can luu san pham vua xem.
        $this->assertEquals([$product1->id], session('recently_viewed'));

        // Xem chi tiet san pham thu hai.
        $response = $this->get(route('products.show', $product2));
        $response->assertStatus(200);

        // San pham moi xem phai dung truoc san pham cu.
        $this->assertEquals([$product2->id, $product1->id], session('recently_viewed'));

        // View chi nen nhan san pham da xem truoc do.
        $response->assertViewHas('recentlyViewedProducts', function ($products) use ($product1) {
            return $products->count() === 1 && $products->first()->id === $product1->id;
        });
    }
}
