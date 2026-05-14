<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function show(Order $order): View
    {
        return view('payment.demo', [
            'order' => $order->load('items'),
            'methodLabels' => $this->methodLabels(),
        ]);
    }

    public function confirm(Order $order): RedirectResponse
    {
        $order->update([
            'payment_status' => 'paid',
            'status' => 'processing',
        ]);

        return to_route('checkout.success', $order)->with('status', 'Thanh toan demo thanh cong.');
    }

    private function methodLabels(): array
    {
        return [
            'cod' => 'Thanh toan khi nhan hang',
            'bank_transfer' => 'Chuyen khoan ngan hang',
            'wallet' => 'Vi dien tu demo',
        ];
    }
}
