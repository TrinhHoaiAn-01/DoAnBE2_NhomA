<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class RecentlyViewedProductTest extends TestCase
{
    use DatabaseTransactions;

    public function test_recently_viewed_products_are_stored_in_session(): void
    {
        // 1. Create a Category
        $category = Category::query()->create([
            'name' => 'Test Category',
            'slug' => 'test-category',
            'is_active' => true,
        ]);

        // 2. Create two Products
        $product1 = Product::query()->create([
            'category_id' => $category->id,
            'name' => 'Product 1',
            'slug' => 'product-1',
            'sku' => 'PROD1',
            'price' => 100000,
            'stock' => 10,
            'is_active' => true,
        ]);

        $product2 = Product::query()->create([
            'category_id' => $category->id,
            'name' => 'Product 2',
            'slug' => 'product-2',
            'sku' => 'PROD2',
            'price' => 200000,
            'stock' => 5,
            'is_active' => true,
        ]);

        // 3. Visit Product 1 detail page
        $response = $this->get(route('products.show', $product1));
        $response->assertStatus(200);

        // Session should contain Product 1 ID
        $this->assertEquals([$product1->id], session('recently_viewed'));

        // 4. Visit Product 2 detail page
        $response = $this->get(route('products.show', $product2));
        $response->assertStatus(200);

        // Session should now contain Product 2 ID then Product 1 ID
        $this->assertEquals([$product2->id, $product1->id], session('recently_viewed'));

        // The view for Product 2 should receive Product 1 in $recentlyViewedProducts
        $response->assertViewHas('recentlyViewedProducts', function ($products) use ($product1) {
            return $products->count() === 1 && $products->first()->id === $product1->id;
        });
    }
}
