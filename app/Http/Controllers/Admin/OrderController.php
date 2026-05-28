<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Support\DeliveryTimeSlot;
use App\Support\ShippingFeeCalculator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controller OrderController (Admin)
 *
 * Quản lý danh sách các đơn đặt hàng (Order) của hệ thống trong trang quản trị.
 * Hỗ trợ các chức năng: Xem danh sách đơn hàng (phân trang, lọc theo từ khóa tìm kiếm và trạng thái),
 * xem chi tiết thông tin đơn đặt hàng (thông tin khách hàng, sản phẩm, lịch trình giao hàng),
 * và cập nhật trạng thái đơn hàng (chờ xử lý, đang giao, đã giao, đã hủy...).
 */
class OrderController extends Controller
{
    /**
     * Hiển thị danh sách đơn hàng có áp dụng bộ lọc tìm kiếm và trạng thái.
     * Thống kê số lượng đơn hàng theo các trạng thái chính để hiển thị nhanh trên UI.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request): View
    {
        // 1. Nhận các tham số tìm kiếm (mã đơn, tên, SĐT khách) và trạng thái đơn hàng
        $search = trim((string) $request->string('search'));
        $status = $request->string('status')->toString();

        // 2. Thực hiện truy vấn danh sách đơn hàng
        $orders = Order::query()
            ->withCount('items') // Đếm số loại sản phẩm trong đơn hàng
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($nested) use ($search): void {
                    // Tìm kiếm theo mã đơn hàng, tên khách hàng hoặc số điện thoại khách hàng
                    $nested
                        ->where('code', 'like', "%{$search}%")
                        ->orWhere('customer_name', 'like', "%{$search}%")
                        ->orWhere('customer_phone', 'like', "%{$search}%");
                });
            })
            ->when($status !== '', function ($query) use ($status): void {
                // Lọc đơn hàng theo trạng thái cụ thể
                $query->where('status', $status);
            })
            ->latest() // Đơn hàng mới nhất lên đầu
            ->paginate(15) // Phân trang 15 đơn hàng trên trang
            ->withQueryString();

        return view('admin.orders.index', [
            'orders' => $orders,
            'search' => $search,
            'status' => $status,
            'statusOptions' => $this->statusOptions(), // Danh sách các trạng thái đơn hàng dịch sang tiếng Việt
            // Đếm số lượng đơn hàng nhanh theo từng trạng thái để làm các tab bộ lọc nhanh
            'pendingCount' => Order::query()->where('status', 'pending')->count(),
            'processingCount' => Order::query()->where('status', 'processing')->count(),
            'completedCount' => Order::query()->where('status', 'completed')->count(),
        ]);
    }

    /**
     * Hiển thị trang chi tiết một đơn đặt hàng.
     *
     * @param \App\Models\Order $order
     * @return \Illuminate\View\View
     */
    public function show(Order $order): View
    {
        return view('admin.orders.show', [
            'order' => $order->load('items.product', 'user'), // Eager load thông tin sản phẩm và người dùng đặt hàng
            'statusOptions' => $this->statusOptions(),
            'shippingDistrictLabel' => ShippingFeeCalculator::districtLabel($order->shipping_district),
            'shippingServiceLabel' => ShippingFeeCalculator::serviceLabel($order->shipping_service),
            'deliveryTimeSlotLabel' => DeliveryTimeSlot::label($order->delivery_time_slot),
        ]);
    }

    /**
     * Xử lý cập nhật trạng thái của đơn hàng.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Order $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Order $order): RedirectResponse
    {
        // 1. Xác thực trạng thái đơn hàng mới phải nằm trong danh sách hợp lệ
        $data = $request->validate([
            'status' => ['required', 'in:pending,processing,shipping,completed,cancelled'],
        ]);

        // 2. Cập nhật vào cơ sở dữ liệu
        $order->update($data);

        return to_route('admin.orders.show', $order)->with('status', 'Đã cập nhật trạng thái đơn hàng.');
    }

    /**
     * Danh sách các trạng thái đơn hàng được dịch sang tiếng Việt.
     *
     * @return array
     */
    private function statusOptions(): array
    {
        return [
            'pending' => 'Chờ xử lý',
            'processing' => 'Đang xử lý',
            'shipping' => 'Đang giao',
            'completed' => 'Hoàn tất',
            'cancelled' => 'Đã hủy',
        ];
    }
}

