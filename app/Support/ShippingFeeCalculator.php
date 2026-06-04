<?php

namespace App\Support;

/**
 * Lớp hỗ trợ ShippingFeeCalculator
 *
 * Tính toán phí vận chuyển (Shipping Fee) dựa trên khu vực giao hàng (quận/huyện)
 * và hình thức/dịch vụ vận chuyển (tiêu chuẩn hoặc giao nhanh).
 * Tự động xử lý chính sách miễn phí vận chuyển (Freeship) cho các đơn hàng lớn.
 */
class ShippingFeeCalculator
{
    /**
     * Danh sách các phân vùng địa lý giao hàng và mức phí cơ bản.
     *
     * @return array
     */
    public static function districts(): array
    {
        return [
            'noi_thanh' => [
                'label' => 'Nội thành',
                'base_fee' => 18000, // Phí ship nội thành cơ bản
            ],
            'ngoai_thanh' => [
                'label' => 'Ngoại thành',
                'base_fee' => 28000, // Phí ship ngoại thành cơ bản
            ],
            'tinh_thanh' => [
                'label' => 'Tỉnh thành khác',
                'base_fee' => 42000, // Phí ship liên tỉnh
            ],
        ];
    }

    /**
     * Danh sách các hình thức/dịch vụ giao hàng và phụ phí tương ứng.
     *
     * @return array
     */
    public static function services(): array
    {
        return [
            'standard' => [
                'label' => 'Giao tiêu chuẩn',
                'extra_fee' => 0, // Không tính thêm phụ phí
            ],
            'express' => [
                'label' => 'Giao nhanh',
                'extra_fee' => 15000, // Tính thêm phụ phí 15.000đ giao nhanh
            ],
        ];
    }

    /**
     * Tính toán phí giao hàng thực tế của đơn hàng.
     * Chính sách: Miễn phí vận chuyển (0đ) cho đơn hàng có giá trị từ 500,000đ trở lên khi chọn Giao tiêu chuẩn.
     *
     * @param float $subtotal Giá trị tạm tính của đơn hàng (chưa gồm ship và giảm giá)
     * @param string $district Mã khu vực giao hàng (noi_thanh, ngoai_thanh, tinh_thanh)
     * @param string $service Mã hình thức giao hàng (standard, express)
     * @return float Phí vận chuyển cuối cùng
     */
    public static function calculate(float $subtotal, string $district, string $service): float
    {
        // Chính sách Freeship: Đơn hàng >= 500.000đ và chọn Giao tiêu chuẩn thì được miễn phí ship hoàn toàn
        if ($subtotal >= 500000 && $service === 'standard') {
            return 0;
        }

        // Lấy phí ship vùng cơ bản (mặc định lấy phí nội thành nếu không tìm thấy vùng khớp)
        $districtFee = self::districts()[$district]['base_fee'] ?? self::districts()['noi_thanh']['base_fee'];
        
        // Lấy phụ phí dịch vụ (mặc định bằng 0 nếu không tìm thấy dịch vụ khớp)
        $serviceFee = self::services()[$service]['extra_fee'] ?? 0;

        return $districtFee + $serviceFee;
    }

    /**
     * Lấy nhãn tiếng Việt tương ứng của phân vùng giao hàng.
     *
     * @param string|null $district Mã phân vùng
     * @return string
     */
    public static function districtLabel(?string $district): string
    {
        return self::districts()[$district]['label'] ?? 'Nội thành';
    }

    /**
     * Lấy nhãn hiển thị tiếng Việt tương ứng của dịch vụ giao hàng.
     *
     * @param string|null $service Mã dịch vụ giao hàng
     * @return string
     */
    public static function serviceLabel(?string $service): string
    {
        return self::services()[$service]['label'] ?? 'Giao tiêu chuẩn';
    }
}

