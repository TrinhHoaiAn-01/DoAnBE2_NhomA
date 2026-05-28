<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Support\DeliveryTimeSlot;
use App\Support\OrderStatus;
use App\Support\ShippingFeeCalculator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderHistoryController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->string('status')->toString();

        // Chi lay don hang cua tai khoan dang dang nhap de tranh lo thong tin.
        $orders = Order::query()
            ->withCount('items')
            ->where('user_id', $request->user()->id)
            ->when($status !== '', function ($query) use ($status): void {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('orders.index', [
            'orders' => $orders,
            'status' => $status,
            'statusOptions' => OrderStatus::labels(),
        ]);
    }

    public function show(Request $request, Order $order): View
    {
        abort_unless((int) $order->user_id === (int) $request->user()->id, 404);

        // Hien thi chi tiet de khach hang theo doi tien do don hang.
        return view('orders.show', [
            'order' => $order->load('items.product'),
            'statusOptions' => OrderStatus::labels(),
            'trackingSteps' => OrderStatus::steps($order->status),
            'shippingDistrictLabel' => ShippingFeeCalculator::districtLabel($order->shipping_district),
            'shippingServiceLabel' => ShippingFeeCalculator::serviceLabel($order->shipping_service),
            'deliveryTimeSlotLabel' => DeliveryTimeSlot::label($order->delivery_time_slot),
        ]);
    }

    public function cancel(Request $request, Order $order): RedirectResponse
    {
        abort_unless((int) $order->user_id === (int) $request->user()->id, 404);

        if (! in_array($order->status, ['pending', 'processing'], true)) {
            return back()->with('error', 'Đơn hàng này không thể hủy ở trạng thái hiện tại.');
        }

        // Cap nhat trang thai huy de admin khong tiep tuc xu ly giao hang.
        $order->update([
            'status' => 'cancelled',
            'payment_status' => $order->payment_status === 'paid' ? 'refund_pending' : $order->payment_status,
        ]);

        return to_route('orders.show', $order)->with('status', 'Đã hủy đơn hàng thành công.');
    }
}
