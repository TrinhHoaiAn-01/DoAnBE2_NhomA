<?php

namespace App\Support;

class OrderStatus
{
    public static function labels(): array
    {
        return [
            'pending' => 'Chờ xử lý',
            'processing' => 'Đang xử lý',
            'shipping' => 'Đang giao',
            'completed' => 'Hoàn tất',
            'cancelled' => 'Đã hủy',
        ];
    }

    public static function label(?string $status): string
    {
        return self::labels()[$status] ?? 'Chưa xác định';
    }

    public static function steps(?string $status): array
    {
        $steps = [
            'pending' => [
                'label' => 'Chờ xử lý',
                'description' => 'NeoMart đã nhận đơn và đang kiểm tra thông tin.',
                'icon' => 'bi-receipt',
            ],
            'processing' => [
                'label' => 'Đang xử lý',
                'description' => 'Nhân viên đang chuẩn bị sản phẩm và xác nhận đơn.',
                'icon' => 'bi-box-seam',
            ],
            'shipping' => [
                'label' => 'Đang giao',
                'description' => 'Đơn hàng đã được bàn giao cho bộ phận vận chuyển.',
                'icon' => 'bi-truck',
            ],
            'completed' => [
                'label' => 'Hoàn tất',
                'description' => 'Đơn hàng đã hoàn tất.',
                'icon' => 'bi-check2-circle',
            ],
        ];

        $activeIndex = array_search($status, array_keys($steps), true);

        if ($status === 'cancelled') {
            return [[
                'label' => 'Đã hủy',
                'description' => 'Đơn hàng đã được hủy theo yêu cầu hoặc bởi quản trị viên.',
                'icon' => 'bi-x-circle',
                'state' => 'danger',
            ]];
        }

        if ($activeIndex === false) {
            $activeIndex = 0;
        }

        return collect($steps)
            ->values()
            ->map(function (array $step, int $index) use ($activeIndex): array {
                // Gan trang thai hien thi cho tung moc tren timeline.
                $step['state'] = $index < $activeIndex ? 'done' : ($index === $activeIndex ? 'active' : 'waiting');

                return $step;
            })
            ->all();
    }

    public static function canBeCancelled(?string $status): bool
    {
        return $status === 'pending';
    }
}
