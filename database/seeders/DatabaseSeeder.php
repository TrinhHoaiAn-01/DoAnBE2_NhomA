<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '0912345678',
            'status' => 'active',
        ]);

        foreach ([
            ['name' => 'Thuc pham', 'icon' => 'fa-apple-whole', 'sort_order' => 1],
            ['name' => 'Do uong', 'icon' => 'fa-bottle-water', 'sort_order' => 2],
            ['name' => 'My pham', 'icon' => 'fa-spray-can-sparkles', 'sort_order' => 3],
            ['name' => 'Gia dung', 'icon' => 'fa-blender', 'sort_order' => 4],
        ] as $category) {
            Category::query()->updateOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($category['name'])],
                $category + ['is_active' => true]
            );
        }

        $products = [
            ['category' => 'Do uong', 'name' => 'Sua tuoi Neo 1L', 'sku' => 'NM-SUA-001', 'brand' => 'NeoFresh', 'price' => 49000, 'original_price' => 59000, 'stock' => 42],
            ['category' => 'Thuc pham', 'name' => 'Gao thom 5kg', 'sku' => 'NM-GAO-005', 'brand' => 'An Lanh', 'price' => 129000, 'original_price' => 145000, 'stock' => 18],
            ['category' => 'Gia dung', 'name' => 'Nuoc rua chen', 'sku' => 'NM-CHEN-003', 'brand' => 'CleanMax', 'price' => 39000, 'original_price' => 45000, 'stock' => 7],
            ['category' => 'My pham', 'name' => 'Dau goi thao moc', 'sku' => 'NM-GOI-002', 'brand' => 'LeafCare', 'price' => 79000, 'original_price' => 99000, 'stock' => 0],
            ['category' => 'Do uong', 'name' => 'Ca phe hoa tan', 'sku' => 'NM-CAPHE-004', 'brand' => 'Morning', 'price' => 69000, 'original_price' => 79000, 'stock' => 25],
            ['category' => 'Thuc pham', 'name' => 'Banh quy bo', 'sku' => 'NM-BANH-006', 'brand' => 'BakeHouse', 'price' => 54000, 'original_price' => 64000, 'stock' => 33],
        ];

        foreach ($products as $item) {
            $category = Category::query()->where('name', $item['category'])->first();

            if (! $category) {
                continue;
            }

            Product::query()->updateOrCreate(
                ['sku' => $item['sku']],
                [
                    'category_id' => $category->id,
                    'name' => $item['name'],
                    'slug' => \Illuminate\Support\Str::slug($item['name']),
                    'brand' => $item['brand'],
                    'description' => 'San pham demo cho tien do phat trien NeoMart.',
                    'price' => $item['price'],
                    'original_price' => $item['original_price'],
                    'stock' => $item['stock'],
                    'image_url' => 'https://placehold.co/600x400?text='.urlencode($item['name']),
                    'is_active' => true,
                ]
            );
        }
    }
}
