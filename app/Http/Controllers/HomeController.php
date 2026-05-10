<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{

    private function getMockCategoryGroups()
    {
        return [
            [
                'name' => 'Thiết bị di động',
                'icon' => 'bi-phone',
                'image' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?q=80&w=400',
                'items' => [
                    ['name' => 'iPhone', 'count' => 120, 'icon' => 'bi-apple'],
                    ['name' => 'Samsung Galaxy', 'count' => 85, 'icon' => 'bi-android2'],
                    ['name' => 'Máy tính bảng', 'count' => 45, 'icon' => 'bi-tablet'],
                ]
            ],
            [
                'name' => 'Máy tính & Laptop',
                'icon' => 'bi-laptop',
                'image' => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?q=80&w=400',
                'items' => [
                    ['name' => 'MacBook', 'count' => 30, 'icon' => 'bi-command'],
                    ['name' => 'Laptop Gaming', 'count' => 55, 'icon' => 'bi-controller'],
                ]
            ],
            [
                'name' => 'Phụ kiện & Âm thanh',
                'icon' => 'bi-headphones',
                'image' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?q=80&w=400',
                'items' => [
                    ['name' => 'Tai nghe', 'count' => 90, 'icon' => 'bi-earbuds'],
                    ['name' => 'Phụ kiện', 'count' => 150, 'icon' => 'bi-usb-c'],
                ]
            ],
            [
                'name' => 'Thiết bị đeo',
                'icon' => 'bi-watch',
                'image' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?q=80&w=400',
                'items' => [
                    ['name' => 'Apple Watch', 'count' => 20, 'icon' => 'bi-watch'],
                    ['name' => 'Vòng đeo tay sức khỏe', 'count' => 15, 'icon' => 'bi-heart-pulse'],
                ]
            ]
        ];
    }
}
