<?php

namespace App\Http\Controllers;

use App\Models\Order;
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
