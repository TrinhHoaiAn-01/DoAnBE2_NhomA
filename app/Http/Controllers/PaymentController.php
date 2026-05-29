<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Controller PaymentController
 *
 * Xử lý mô phỏng quá trình thanh toán (Demo Payment) của khách hàng.
 * Phục vụ cho các phương thức thanh toán trực tuyến (chuyển khoản, ví điện tử) trong môi trường thử nghiệm.
 */
class PaymentController extends Controller
{
    /**
     * Hiển thị trang hướng dẫn và xác nhận thanh toán demo.
     *
     * @param \App\Models\Order $order
     * @return \Illuminate\View\View
     */
    public function show(Order $order): View
    {
        return view('payment.demo', [
            'order' => $order->load('items'), // Eager load danh sách chi tiết đơn hàng
            'methodLabels' => $this->methodLabels(), // Danh sách nhãn các phương thức thanh toán
            'paymentGuides' => $this->paymentGuides(), // Hướng dẫn thanh toán chi tiết cho từng phương thức
        ]);
    }

    /**
     * Xác nhận thanh toán thành công (chức năng mô phỏng/demo).
     * Cập nhật trạng thái thanh toán là đã trả tiền (paid) và trạng thái đơn hàng là đang xử lý (processing).
     *
     * @param \App\Models\Order $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function confirm(Order $order): RedirectResponse
    {
        // Cập nhật thông tin thanh toán và trạng thái của đơn hàng trong Database
        $order->update([
            'payment_status' => 'paid', // Đã thanh toán
            'status' => 'processing',   // Chuyển sang trạng thái đang xử lý chuẩn bị giao hàng
        ]);

        // Chuyển hướng người dùng đến trang hoàn thành đơn hàng thành công
        return to_route('checkout.success', $order)->with('status', 'Thanh toán demo thành công.');
    }

    /**
     * Lấy danh sách nhãn các phương thức thanh toán bằng tiếng Việt.
     *
     * @return array
     */
    private function methodLabels(): array
    {
        return [
            'cod' => 'Thanh toán khi nhận hàng (COD)',
            'bank_transfer' => 'Chuyển khoản ngân hàng',
            'wallet' => 'Ví điện tử demo',
        ];
    }

    /**
     * Hướng dẫn chi tiết cách thanh toán giả lập cho từng phương thức trực tuyến.
     *
     * @return array
     */
    private function paymentGuides(): array
    {
        return [
            'bank_transfer' => [
                'Ngân hàng: NeoMart Bank',
                'Số tài khoản: 1900 2026 0514',
                'Nội dung: Mã đơn hàng của bạn',
            ],
            'wallet' => [
                'Chọn nút xác nhận để mô phỏng rằng ví điện tử đã trừ tiền thành công.',
                'Mã giao dịch demo sẽ được hệ thống tự động ghi nhận sau khi xác nhận thanh toán.',
            ],
        ];
    }
}

