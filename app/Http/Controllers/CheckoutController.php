<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Support\ShippingFeeCalculator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        $items = $this->cartItems($request);

        if (count($items) === 0) {
            return to_route('cart.index')->with('error', 'Gio hang dang trong.');
        }

        return view('checkout.index', [
            'items' => $items,
            'subtotal' => $this->cartTotal($items),
            'shippingFee' => ShippingFeeCalculator::calculate($this->cartTotal($items), 'noi_thanh', 'standard'),
            'shippingDistricts' => ShippingFeeCalculator::districts(),
            'shippingServices' => ShippingFeeCalculator::services(),
            'user' => $request->user(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $items = $this->cartItems($request);

        if (count($items) === 0) {
            return to_route('cart.index')->with('error', 'Gio hang dang trong.');
        }

        $data = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['nullable', 'email', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:30'],
            'shipping_address' => ['required', 'string', 'max:500'],
            'shipping_district' => ['required', 'in:noi_thanh,ngoai_thanh,tinh_thanh'],
            'shipping_service' => ['required', 'in:standard,express'],
            'note' => ['nullable', 'string', 'max:1000'],
            'payment_method' => ['required', 'in:cod,bank_transfer,wallet'],
        ]);

        $subtotal = $this->cartTotal($items);
        $shippingFee = ShippingFeeCalculator::calculate($subtotal, $data['shipping_district'], $data['shipping_service']);

        $paymentStatus = $data['payment_method'] === 'cod' ? 'unpaid' : 'pending';

        $order = DB::transaction(function () use ($request, $data, $items, $subtotal, $shippingFee, $paymentStatus): Order {
            $order = Order::query()->create($data + [
                'user_id' => $request->user()?->id,
                'code' => 'NM'.now()->format('ymdHis').Str::upper(Str::random(3)),
                'payment_status' => $paymentStatus,
                'status' => 'pending',
                'subtotal' => $subtotal,
                'shipping_fee' => $shippingFee,
                'total' => $subtotal + $shippingFee,
            ]);

            foreach ($items as $item) {
                $order->items()->create([
                    'product_id' => $item['product']->id,
                    'product_name' => $item['product']->name,
                    'sku' => $item['product']->sku,
                    'price' => $item['product']->price,
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['subtotal'],
                ]);
            }

            return $order;
        });

        $request->session()->forget('cart');

        if ($order->payment_method !== 'cod') {
            return to_route('payment.demo', $order)->with('status', 'Vui long xac nhan thanh toan demo.');
        }

        return to_route('checkout.success', $order)->with('status', 'Dat hang thanh cong.');
    }

    public function success(Order $order): View
    {
        return view('checkout.success', [
            'order' => $order->load('items'),
        ]);
    }

    private function cartItems(Request $request): array
    {
        $cart = $request->session()->get('cart', []);
        $products = Product::query()
            ->with('category')
            ->whereIn('id', array_keys($cart))
            ->get()
            ->keyBy('id');

        $items = [];

        foreach ($cart as $productId => $quantity) {
            $product = $products->get((int) $productId);

            if (! $product) {
                continue;
            }

            $items[] = [
                'product' => $product,
                'quantity' => (int) $quantity,
                'subtotal' => (float) $product->price * (int) $quantity,
            ];
        }

        return $items;
    }

    private function cartTotal(array $items): float
    {
        return collect($items)->sum('subtotal');
    }

}
