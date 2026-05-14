<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
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
        ]);
    }

    public function update(Request $request, Order $order): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:pending,processing,shipping,completed,cancelled'],
        ]);

        $order->update($data);

        return to_route('admin.orders.show', $order)->with('status', 'Da cap nhat trang thai don hang.');
    }

    private function statusOptions(): array
    {
        return [
            'pending' => 'Cho xu ly',
            'processing' => 'Dang xu ly',
            'shipping' => 'Dang giao',
            'completed' => 'Hoan tat',
            'cancelled' => 'Da huy',
        ];
    }
}
