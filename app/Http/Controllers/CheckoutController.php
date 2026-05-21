<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Promotion;
use App\Support\DeliveryTimeSlot;
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
            return to_route('cart.index')->with('error', 'Giỏ hàng đang trống.');
        }

        $selectedDistrict = $request->old('shipping_district', 'noi_thanh');
        $selectedService = $request->old('shipping_service', 'standard');
        $subtotal = $this->cartTotal($items);
        $promotion = $this->promotionFromCode((string) $request->old('promotion_code'), $subtotal);
        $discountTotal = $promotion?->discountFor($subtotal) ?? 0;

        return view('checkout.index', [
            'items' => $items,
            'subtotal' => $subtotal,
            'shippingFee' => ShippingFeeCalculator::calculate($subtotal, $selectedDistrict, $selectedService),
            'discountTotal' => $discountTotal,
            'shippingDistricts' => ShippingFeeCalculator::districts(),
            'shippingServices' => ShippingFeeCalculator::services(),
            'deliveryTimeSlots' => DeliveryTimeSlot::slots(),
            'defaultDeliveryDate' => now()->addDay()->toDateString(),
            'defaultDeliveryTimeSlot' => DeliveryTimeSlot::defaultSlot(),
            'user' => $request->user(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $items = $this->cartItems($request);

        if (count($items) === 0) {
            return to_route('cart.index')->with('error', 'Giỏ hàng đang trống.');
        }

        $data = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['nullable', 'email', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:30'],
            'shipping_address' => ['required', 'string', 'max:500'],
            'shipping_district' => ['required', 'in:noi_thanh,ngoai_thanh,tinh_thanh'],
            'shipping_service' => ['required', 'in:standard,express'],
            'delivery_date' => ['required', 'date', 'after_or_equal:today', 'before_or_equal:'.now()->addDays(14)->toDateString()],
            'delivery_time_slot' => ['required', 'in:'.implode(',', DeliveryTimeSlot::values())],
            'note' => ['nullable', 'string', 'max:1000'],
            'promotion_code' => ['nullable', 'string', 'max:40'],
            'payment_method' => ['required', 'in:cod,bank_transfer,wallet'],
        ]);

        $subtotal = $this->cartTotal($items);
        $shippingFee = ShippingFeeCalculator::calculate($subtotal, $data['shipping_district'], $data['shipping_service']);
        $promotion = $this->promotionFromCode((string) ($data['promotion_code'] ?? ''), $subtotal);
        $discountTotal = $promotion?->discountFor($subtotal) ?? 0;

        if (($data['promotion_code'] ?? '') !== '' && ! $promotion) {
            return back()->withInput()->with('error', 'Mã giảm giá không hợp lệ hoặc chưa đủ điều kiện áp dụng.');
        }

        $paymentStatus = $data['payment_method'] === 'cod' ? 'unpaid' : 'pending';

        $order = DB::transaction(function () use ($request, $data, $items, $subtotal, $shippingFee, $discountTotal, $promotion, $paymentStatus): Order {
            $order = Order::query()->create($data + [
                'user_id' => $request->user()?->id,
                'promotion_id' => $promotion?->id,
                'promotion_code' => $promotion?->code,
                'code' => 'NM'.now()->format('ymdHis').Str::upper(Str::random(3)),
                'payment_status' => $paymentStatus,
                'status' => 'pending',
                'subtotal' => $subtotal,
                'shipping_fee' => $shippingFee,
                'discount_total' => $discountTotal,
                'total' => max(0, $subtotal + $shippingFee - $discountTotal),
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

            $promotion?->increment('used_count');

            return $order;
        });

        $request->session()->forget('cart');

        if ($order->payment_method !== 'cod') {
            return to_route('payment.demo', $order)->with('status', 'Vui lòng xác nhận thanh toán demo.');
        }

        return to_route('checkout.success', $order)->with('status', 'Đặt hàng thành công.');
    }

    private function promotionFromCode(string $code, float $subtotal): ?Promotion
    {
        $code = trim(mb_strtoupper($code));

        if ($code === '') {
            return null;
        }

        return Promotion::query()
            ->where('code', $code)
            ->where('is_active', true)
            ->where('minimum_order', '<=', $subtotal)
            ->where(function ($query): void {
                $query->whereNull('starts_at')
                    ->orWhere('starts_at', '<=', now());
            })
            ->where(function ($query): void {
                $query->whereNull('ends_at')
                    ->orWhere('ends_at', '>=', now());
            })
            ->where(function ($query): void {
                $query->whereNull('usage_limit')
                    ->orWhereColumn('used_count', '<', 'usage_limit');
            })
            ->first();
    }

    public function success(Order $order): View
    {
        return view('checkout.success', [
            'order' => $order->load('items'),
            'shippingDistrictLabel' => ShippingFeeCalculator::districtLabel($order->shipping_district),
            'shippingServiceLabel' => ShippingFeeCalculator::serviceLabel($order->shipping_service),
            'deliveryTimeSlotLabel' => DeliveryTimeSlot::label($order->delivery_time_slot),
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
