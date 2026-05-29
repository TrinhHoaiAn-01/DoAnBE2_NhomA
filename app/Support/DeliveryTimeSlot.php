<?php

namespace App\Support;

/**
 * Lớp hỗ trợ DeliveryTimeSlot
 *
 * Định nghĩa và quản lý các khung giờ giao hàng (Time Slots) của hệ thống cửa hàng.
 * Cung cấp nhãn hiển thị tiếng Việt, mô tả thời gian thích hợp và các giá trị mặc định cho đơn giao.
 */
class DeliveryTimeSlot
{
    /**
     * Lấy danh sách các khung giờ giao hàng có cấu hình chi tiết.
     *
     * @return array
     */
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

    /**
     * Lấy danh sách các khóa định danh (keys) của khung giờ.
     *
     * @return array
     */
    public static function values(): array
    {
        return array_keys(self::slots());
    }

    /**
     * Khung giờ giao hàng mặc định khi khách hàng chưa chọn.
     *
     * @return string
     */
    public static function defaultSlot(): string
    {
        return 'afternoon';
    }

    /**
     * Lấy nhãn hiển thị tiếng Việt tương ứng với mã khung giờ.
     *
     * @param string|null $slot Mã khung giờ giao hàng
     * @return string Nhãn tiếng Việt hiển thị
     */
    public static function label(?string $slot): string
    {
        return self::slots()[$slot]['label'] ?? 'Chưa chọn khung giờ';
    }
}

