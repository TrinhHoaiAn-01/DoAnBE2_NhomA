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

        // Xu ly huy don se duoc bo sung sau khi xac dinh dieu kien nghiep vu.
        return back()->with('error', 'Chức năng hủy đơn đang được chuẩn bị.');
    }
}
