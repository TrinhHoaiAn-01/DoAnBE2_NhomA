<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesCrudSafety;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Support\DeliveryTimeSlot;
use App\Support\ShippingFeeCalculator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    use HandlesCrudSafety;

    public function index(Request $request): View
    {
        $search = trim((string) $request->string('search'));
        $status = $request->string('status')->toString();

        $orders = Order::query()
            ->withCount('items')
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($nested) use ($search): void {
                    $nested
                        ->where('code', 'like', "%{$search}%")
                        ->orWhere('customer_name', 'like', "%{$search}%")
                        ->orWhere('customer_phone', 'like', "%{$search}%");
                });
            })
            ->when($status !== '', function ($query) use ($status): void {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.orders.index', [
            'orders' => $orders,
            'search' => $search,
            'status' => $status,
            'statusOptions' => $this->statusOptions(),
            'pendingCount' => Order::query()->where('status', 'pending')->count(),
            'processingCount' => Order::query()->where('status', 'processing')->count(),
            'completedCount' => Order::query()->where('status', 'completed')->count(),
        ]);
    }

    public function show(Order $order): View
    {
        return view('admin.orders.show', [
            'order' => $order->load('items.product', 'user'),
            'statusOptions' => $this->statusOptions(),
            'shippingDistrictLabel' => ShippingFeeCalculator::districtLabel($order->shipping_district),
            'shippingServiceLabel' => ShippingFeeCalculator::serviceLabel($order->shipping_service),
            'deliveryTimeSlotLabel' => DeliveryTimeSlot::label($order->delivery_time_slot),
        ]);
    }

    public function update(Request $request, Order $order): RedirectResponse
    {
        $data = $this->validateCrud($request, [
            'status' => ['required', 'in:pending,processing,shipping,completed,cancelled'],
        ]);

        return $this->runCrudOperation(function () use ($request, $order, $data): RedirectResponse {
            $this->transaction(function () use ($request, $order, $data): void {
                $lockedOrder = $this->lockForCrud($order);
                $this->assertFreshRecord($request, $lockedOrder, 'đơn hàng');
                $lockedOrder->update($data);
            });

            return to_route('admin.orders.show', $order)
                ->with('status', 'Đã cập nhật trạng thái đơn hàng. Hệ thống đã kiểm tra phiên chỉnh sửa trước khi lưu.');
        }, 'cập nhật đơn hàng');
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
