<?php

namespace App\Support;

/**
 * Lớp hỗ trợ OrderStatus
 *
 * Định nghĩa nhãn trạng thái tiếng Việt, theo dõi các bước trên hành trình giao hàng (Timeline Steps),
 * và xác định điều kiện hủy đơn hàng từ phía Client.
 */
class OrderStatus
{
    /**
     * Lấy danh sách các nhãn trạng thái đơn hàng bằng tiếng Việt.
     *
     * @return array
     */
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

    /**
     * Lấy nhãn tiếng Việt của một trạng thái đơn hàng cụ thể.
     *
     * @param string|null $status Mã trạng thái đơn hàng
     * @return string
     */
    public static function label(?string $status): string
    {
        return self::labels()[$status] ?? 'Chưa xác định';
    }

    /**
     * Tạo danh sách các bước tiến trình vận đơn phục vụ việc hiển thị Timeline theo dõi đơn hàng.
     *
     * @param string|null $status Trạng thái hiện tại của đơn hàng
     * @return array Danh sách các bước kèm theo trạng thái hiển thị (done, active, waiting hoặc danger)
     */
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

        // Tìm vị trí của trạng thái hiện tại trong tiến trình chuẩn
        $activeIndex = array_search($status, array_keys($steps), true);

        // Trường hợp đơn hàng bị hủy (cancelled)
        if ($status === 'cancelled') {
            return [[
                'label' => 'Đã hủy',
                'description' => 'Đơn hàng đã được hủy theo yêu cầu hoặc bởi quản trị viên.',
                'icon' => 'bi-x-circle',
                'state' => 'danger', // Hiển thị màu đỏ cảnh báo
            ]];
        }

        if ($activeIndex === false) {
            $activeIndex = 0;
        }

        // Tạo trạng thái hiển thị (done - đã qua, active - hiện tại, waiting - sắp tới) cho mỗi bước
        return collect($steps)
            ->values()
            ->map(function (array $step, int $index) use ($activeIndex): array {
                // Gán trạng thái hiển thị cho từng mốc trên timeline.
                $step['state'] = $index < $activeIndex ? 'done' : ($index === $activeIndex ? 'active' : 'waiting');

                return $step;
            })
            ->all();
    }

    /**
     * Kiểm tra xem đơn hàng có thể hủy được từ phía khách hàng hay không.
     * Khách hàng chỉ có quyền hủy đơn hàng khi đơn hàng đang ở trạng thái 'chờ xử lý' (pending).
     *
     * @param string|null $status
     * @return bool
     */
    public static function canBeCancelled(?string $status): bool
    {
        return $status === 'pending';
    }
}

