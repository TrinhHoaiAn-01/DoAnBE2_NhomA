<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Support\DeliveryTimeSlot;
use App\Support\ShippingFeeCalculator;
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
            'statusOptions' => $this->statusOptions(),
        ]);
    }

    public function show(Order $order): View
    {
        // Hien thi chi tiet de khach hang theo doi tien do don hang.
        return view('orders.show', [
            'order' => $order->load('items.product'),
            'statusOptions' => $this->statusOptions(),
            'shippingDistrictLabel' => ShippingFeeCalculator::districtLabel($order->shipping_district),
            'shippingServiceLabel' => ShippingFeeCalculator::serviceLabel($order->shipping_service),
            'deliveryTimeSlotLabel' => DeliveryTimeSlot::label($order->delivery_time_slot),
        ]);
    }

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
