<?php

namespace App\Support;

class ShippingFeeCalculator
{
    public static function districts(): array
    {
        return [
            'noi_thanh' => [
                'label' => 'Nội thành',
                'base_fee' => 18000,
            ],
            'ngoai_thanh' => [
                'label' => 'Ngoại thành',
                'base_fee' => 28000,
            ],
            'tinh_thanh' => [
                'label' => 'Tỉnh thành khác',
                'base_fee' => 42000,
            ],
        ];
    }

    public static function services(): array
    {
        return [
            'standard' => [
                'label' => 'Giao tiêu chuẩn',
                'extra_fee' => 0,
            ],
            'express' => [
                'label' => 'Giao nhanh',
                'extra_fee' => 15000,
            ],
        ];
    }

    public static function calculate(float $subtotal, string $district, string $service): float
    {
        if ($subtotal >= 500000 && $service === 'standard') {
            return 0;
        }

        $districtFee = self::districts()[$district]['base_fee'] ?? self::districts()['noi_thanh']['base_fee'];
        $serviceFee = self::services()[$service]['extra_fee'] ?? 0;

        return $districtFee + $serviceFee;
    }

    public static function districtLabel(?string $district): string
    {
        return self::districts()[$district]['label'] ?? 'Nội thành';
    }

    public static function serviceLabel(?string $service): string
    {
        return self::services()[$service]['label'] ?? 'Giao tiêu chuẩn';
    }
}
