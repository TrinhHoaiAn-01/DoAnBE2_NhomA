<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Thuc pham',
                'icon' => 'fa-apple-whole',
                'description' => 'Nhom hang thuc pham kho va thuc pham dong goi.',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Do uong',
                'icon' => 'fa-bottle-water',
                'description' => 'Nuoc giai khat, sua, ca phe va do uong tien loi.',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'My pham',
                'icon' => 'fa-spray-can-sparkles',
                'description' => 'Cham soc ca nhan va san pham lam dep co ban.',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Gia dung',
                'icon' => 'fa-blender',
                'description' => 'Vat dung ve sinh va tien ich trong gia dinh.',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Khuyen mai',
                'icon' => 'fa-tags',
                'description' => 'Nhom san pham dang ap dung chuong trinh giam gia.',
                'sort_order' => 5,
                'is_active' => false,
            ],
        ];

        foreach ($categories as $category) {
            Category::query()->updateOrCreate(
                ['slug' => Str::slug($category['name'])],
                $category + ['slug' => Str::slug($category['name'])]
            );
        }

        $products = [
            [
                'category' => 'Do uong',
                'name' => 'Sua tuoi Neo 1L',
                'sku' => 'NM-SUA-001',
                'brand' => 'NeoFresh',
                'description' => 'Sua tuoi tiet trung dung tich 1 lit.',
                'price' => 49000,
                'original_price' => 59000,
                'stock' => 42,
            ],
            [
                'category' => 'Thuc pham',
                'name' => 'Gao thom 5kg',
                'sku' => 'NM-GAO-005',
                'brand' => 'An Lanh',
                'description' => 'Gao thom dong tui 5kg cho gia dinh.',
                'price' => 129000,
                'original_price' => 145000,
                'stock' => 18,
            ],
            [
                'category' => 'Gia dung',
                'name' => 'Nuoc rua chen CleanMax',
                'sku' => 'NM-CHEN-003',
                'brand' => 'CleanMax',
                'description' => 'Nuoc rua chen huong chanh chai 750ml.',
                'price' => 39000,
                'original_price' => 45000,
                'stock' => 7,
            ],
            [
                'category' => 'My pham',
                'name' => 'Dau goi thao moc',
                'sku' => 'NM-GOI-002',
                'brand' => 'LeafCare',
                'description' => 'Dau goi thao moc chai 500ml.',
                'price' => 79000,
                'original_price' => 99000,
                'stock' => 0,
            ],
            [
                'category' => 'Do uong',
                'name' => 'Ca phe hoa tan Morning',
                'sku' => 'NM-CAPHE-004',
                'brand' => 'Morning',
                'description' => 'Hop ca phe hoa tan 20 goi.',
                'price' => 69000,
                'original_price' => 79000,
                'stock' => 25,
            ],
            [
                'category' => 'Thuc pham',
                'name' => 'Banh quy bo BakeHouse',
                'sku' => 'NM-BANH-006',
                'brand' => 'BakeHouse',
                'description' => 'Banh quy bo hop 300g.',
                'price' => 54000,
                'original_price' => 64000,
                'stock' => 33,
            ],
            [
                'category' => 'Gia dung',
                'name' => 'Khan giay nha bep',
                'sku' => 'NM-KHAN-007',
                'brand' => 'CleanMax',
                'description' => 'Khan giay da dung cho khu vuc bep.',
                'price' => 32000,
                'original_price' => null,
                'stock' => 9,
            ],
            [
                'category' => 'My pham',
                'name' => 'Sua rua mat LeafCare',
                'sku' => 'NM-SRM-008',
                'brand' => 'LeafCare',
                'description' => 'Sua rua mat diu nhe dung tich 120ml.',
                'price' => 89000,
                'original_price' => 109000,
                'stock' => 16,
            ],
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
                    'slug' => Str::slug($item['name']),
                    'brand' => $item['brand'],
                    'description' => $item['description'],
                    'price' => $item['price'],
                    'original_price' => $item['original_price'],
                    'stock' => $item['stock'],
                    'image_url' => 'https://placehold.co/600x400?text=' . urlencode($item['name']),
                    'is_active' => true,
                ]
            );
        }
    }
}
