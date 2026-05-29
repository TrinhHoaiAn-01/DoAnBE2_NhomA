<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Support\DeliveryTimeSlot;
use App\Support\OrderStatus;
use App\Support\ShippingFeeCalculator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controller OrderHistoryController
 *
 * Quản lý lịch sử mua hàng dành cho khách hàng đã đăng nhập.
 * Cho phép xem danh sách đơn hàng (phân trang, lọc theo trạng thái), xem chi tiết đơn hàng,
 * và thực hiện hủy đơn hàng nếu đơn hàng chưa được xử lý (trước khi giao hàng).
 */
class OrderHistoryController extends Controller
{
    /**
     * Hiển thị danh sách lịch sử đơn hàng của người dùng hiện tại.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request): View
    {
        // Lấy bộ lọc trạng thái đơn hàng từ query string
        $status = $request->string('status')->toString();

        // 1. Chỉ truy vấn đơn hàng thuộc sở hữu của tài khoản đang đăng nhập để bảo mật thông tin
        $orders = Order::query()
            ->withCount('items') // Đếm số lượng sản phẩm trong đơn hàng
            ->where('user_id', $request->user()->id)
            ->when($status !== '', function ($query) use ($status): void {
                // Lọc theo trạng thái đơn hàng nếu người dùng chọn bộ lọc
                $query->where('status', $status);
            })
            ->latest() // Đơn hàng mới nhất xếp lên đầu
            ->paginate(10) // Phân trang 10 đơn hàng trên mỗi trang
            ->withQueryString(); // Giữ lại tham số lọc khi chuyển trang

        // 2. Trả về view kèm thông tin đơn hàng và danh sách nhãn trạng thái hợp lệ
        return view('orders.index', [
            'orders' => $orders,
            'status' => $status,
            'statusOptions' => OrderStatus::labels(), // Các trạng thái đơn hàng khả dụng
        ]);
    }

    /**
     * Hiển thị chi tiết một đơn hàng cụ thể của người dùng.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Order $order
     * @return \Illuminate\View\View
     */
    public function show(Request $request, Order $order): View
    {
        // Bảo mật: Nếu đơn hàng không thuộc về người dùng đang đăng nhập, trả về lỗi 404
        abort_unless((int) $order->user_id === (int) $request->user()->id, 404);

        // Hiển thị thông tin chi tiết và tiến trình xử lý đơn hàng (tracking steps) để khách hàng tiện theo dõi
        return view('orders.show', [
            'order' => $order->load('items.product'), // Eager load các sản phẩm trong đơn hàng
            'statusOptions' => OrderStatus::labels(),
            'trackingSteps' => OrderStatus::steps($order->status), // Các bước tiến trình giao hàng
            'canCancel' => OrderStatus::canBeCancelled($order->status), // Đơn hàng còn có thể hủy không
            'shippingDistrictLabel' => ShippingFeeCalculator::districtLabel($order->shipping_district),
            'shippingServiceLabel' => ShippingFeeCalculator::serviceLabel($order->shipping_service),
            'deliveryTimeSlotLabel' => DeliveryTimeSlot::label($order->delivery_time_slot),
        ]);
    }

    /**
     * Xử lý yêu cầu hủy đơn hàng từ phía khách hàng.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Order $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel(Request $request, Order $order): RedirectResponse
    {
        // Bảo mật: Chặn nếu người dùng hiện tại không phải chủ nhân đơn hàng
        abort_unless((int) $order->user_id === (int) $request->user()->id, 404);

        // Kiểm tra xem trạng thái đơn hàng hiện tại có cho phép hủy hay không (chưa xử lý/chờ xác nhận)
        if (! OrderStatus::canBeCancelled($order->status)) {
            return back()->with('error', 'Chỉ có thể hủy đơn trước khi đơn được xử lý.');
        }

        // Cập nhật trạng thái hủy đơn và chuyển trạng thái thanh toán sang hoàn tiền nếu đã thanh toán trước đó
        $order->update([
            'status' => 'cancelled', // Cập nhật trạng thái thành Đã hủy
            'payment_status' => $order->payment_status === 'paid' ? 'refund_pending' : $order->payment_status, // Nếu đã thanh toán, chuyển sang Chờ hoàn tiền
        ]);

        return to_route('orders.show', $order)->with('status', 'Đã hủy đơn hàng thành công.');
    }
}

