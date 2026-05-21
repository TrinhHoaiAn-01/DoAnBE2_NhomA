<?php

namespace App\Support;

class DeliveryTimeSlot
{
    public static function slots(): array
    {
        return [
            'morning' => [
                'label' => 'Sáng 08:00 - 11:00',
                'description' => 'Phù hợp đơn giao trước giờ trưa.',
            ],
            'noon' => [
                'label' => 'Trưa 11:00 - 13:30',
                'description' => 'Ưu tiên khách nhận tại nhà hoặc văn phòng.',
            ],
            'afternoon' => [
                'label' => 'Chiều 13:30 - 17:30',
                'description' => 'Khung giờ giao phổ biến trong ngày.',
            ],
            'evening' => [
                'label' => 'Tối 18:00 - 21:00',
                'description' => 'Thuận tiện cho khách bận giờ hành chính.',
            ],
        ];
    }

    public static function values(): array
    {
        return array_keys(self::slots());
    }

    public static function defaultSlot(): string
    {
        return 'afternoon';
    }

    public static function label(?string $slot): string
    {
        return self::slots()[$slot]['label'] ?? 'Chưa chọn khung giờ';
    }
}
