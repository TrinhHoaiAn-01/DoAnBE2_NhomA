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

/**
 * Controller CheckoutController
 *
 * Xử lý quy trình thanh toán đơn hàng (Checkout).
 * Tính toán phí vận chuyển, áp dụng khuyến mãi, tạo đơn hàng mới thông qua DB Transaction,
 * giảm trừ giỏ hàng và chuyển hướng đến trang thanh toán demo hoặc trang thành công.
 */
class CheckoutController extends Controller
{
    /**
     * Hiển thị trang thanh toán và điền sẵn các thông số mặc định.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index(Request $request): View|RedirectResponse
    {
        // 1. Lấy toàn bộ sản phẩm trong giỏ hàng
        $items = $this->cartItems($request);

        // Nếu giỏ hàng rỗng, quay lại trang giỏ hàng kèm cảnh báo
        if (count($items) === 0) {
            return to_route('cart.index')->with('error', 'Giỏ hàng đang trống.');
        }

        // 2. Thiết lập quận/huyện và dịch vụ vận chuyển mặc định (hoặc từ giá trị cũ flash session)
        $selectedDistrict = $request->old('shipping_district', 'noi_thanh');
        $selectedService = $request->old('shipping_service', 'standard');
        
        // 3. Tính tiền tạm tính (chưa có ship và giảm giá)
        $subtotal = $this->cartTotal($items);
        
        // 4. Lấy thông tin khuyến mãi nếu có áp dụng mã từ trước
        $promotion = $this->promotionFromCode((string) $request->old('promotion_code'), $subtotal);
        $discountTotal = $promotion?->discountFor($subtotal) ?? 0;

        // 5. Trả về view checkout kèm dữ liệu đã chuẩn bị
        return view('checkout.index', [
            'items' => $items,
            'subtotal' => $subtotal,
            'shippingFee' => ShippingFeeCalculator::calculate($subtotal, $selectedDistrict, $selectedService),
            'discountTotal' => $discountTotal,
            'shippingDistricts' => ShippingFeeCalculator::districts(),
            'shippingServices' => ShippingFeeCalculator::services(),
            'deliveryTimeSlots' => DeliveryTimeSlot::slots(),
            'defaultDeliveryDate' => now()->addDay()->toDateString(), // Mặc định giao hàng vào ngày mai
            'defaultDeliveryTimeSlot' => DeliveryTimeSlot::defaultSlot(),
            'user' => $request->user(), // Người dùng đăng nhập (nếu có)
        ]);
    }

    /**
     * Xử lý lưu đơn hàng và thông tin vận chuyển khi người dùng nhấn đặt hàng.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Lấy sản phẩm trong giỏ hàng
        $items = $this->cartItems($request);

        if (count($items) === 0) {
            return to_route('cart.index')->with('error', 'Giỏ hàng đang trống.');
        }

        // 2. Xác thực dữ liệu đầu vào người dùng cung cấp
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

        // 3. Tính toán lại chi phí trên phía Backend để chống giả mạo giá
        $subtotal = $this->cartTotal($items);
        $shippingFee = ShippingFeeCalculator::calculate($subtotal, $data['shipping_district'], $data['shipping_service']);
        $promotion = $this->promotionFromCode((string) ($data['promotion_code'] ?? ''), $subtotal);
        $discountTotal = $promotion?->discountFor($subtotal) ?? 0;
        
        // Chuẩn hóa ghi chú đơn hàng
        $data['note'] = $this->normalizeOrderNote($data['note'] ?? null);

        // 4. Nếu người dùng nhập mã giảm giá mà không hợp lệ
        if (($data['promotion_code'] ?? '') !== '' && ! $promotion) {
            return back()->withInput()->with('error', 'Mã giảm giá không hợp lệ hoặc chưa đủ điều kiện áp dụng.');
        }

        // Trạng thái thanh toán mặc định
        $paymentStatus = $data['payment_method'] === 'cod' ? 'unpaid' : 'pending';

        // 5. Thực hiện tạo đơn hàng bằng DB Transaction để đảm bảo tính toàn vẹn dữ liệu
        $order = DB::transaction(function () use ($request, $data, $items, $subtotal, $shippingFee, $discountTotal, $promotion, $paymentStatus): Order {
            
            // 5.1 Tạo đơn hàng chính (Order)
            $order = Order::query()->create($data + [
                'user_id' => $request->user()?->id,
                'promotion_id' => $promotion?->id,
                'promotion_code' => $promotion?->code,
                'code' => 'NM'.now()->format('ymdHis').Str::upper(Str::random(3)), // Mã đơn hàng ngẫu nhiên duy nhất
                'payment_status' => $paymentStatus,
                'status' => 'pending',
                'subtotal' => $subtotal,
                'shipping_fee' => $shippingFee,
                'discount_total' => $discountTotal,
                'total' => max(0, $subtotal + $shippingFee - $discountTotal), // Đảm bảo tổng tiền không âm
            ]);

            // 5.2 Tạo chi tiết từng sản phẩm trong đơn hàng (Order Items)
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

            // 5.3 Tăng số lần sử dụng của mã khuyến mãi (nếu có)
            $promotion?->increment('used_count');

            return $order;
        });

        // 6. Xóa giỏ hàng hiện tại trong Session sau khi tạo đơn hàng thành công
        $request->session()->forget('cart');

        // 7. Chuyển hướng theo phương thức thanh toán
        if ($order->payment_method !== 'cod') {
            // Nếu chuyển khoản hoặc ví, chuyển tới trang thanh toán demo
            return to_route('payment.demo', $order)->with('status', 'Vui lòng xác nhận thanh toán demo.');
        }

        // Nếu thanh toán khi nhận hàng (COD), chuyển thẳng tới trang thành công
        return to_route('checkout.success', $order)->with('status', 'Đặt hàng thành công.');
    }

    /**
     * Truy vấn thông tin khuyến mãi hợp lệ từ mã code.
     *
     * @param string $code
     * @param float $subtotal
     * @return \App\Models\Promotion|null
     */
    private function promotionFromCode(string $code, float $subtotal): ?Promotion
    {
        $code = trim(mb_strtoupper($code));

        if ($code === '') {
            return null;
        }

        return Promotion::query()
            ->where('code', $code)
            ->where('is_active', true)
            ->where('minimum_order', '<=', $subtotal) // Đơn hàng phải đạt giá trị tối thiểu
            ->where(function ($query): void {
                $query->whereNull('starts_at') // Chưa tới hạn bắt đầu
                    ->orWhere('starts_at', '<=', now());
            })
            ->where(function ($query): void {
                $query->whereNull('ends_at') // Chưa hết hạn sử dụng
                    ->orWhere('ends_at', '>=', now());
            })
            ->where(function ($query): void {
                $query->whereNull('usage_limit') // Chưa vượt giới hạn số lượt sử dụng
                    ->orWhereColumn('used_count', '<', 'usage_limit');
            })
            ->first();
    }

    /**
     * Chuẩn hóa và làm sạch ghi chú đơn hàng.
     *
     * @param string|null $note
     * @return string|null
     */
    private function normalizeOrderNote(?string $note): ?string
    {
        // Loại bỏ khoảng trắng thừa để tránh lưu chuỗi rỗng
        $note = trim((string) $note);

        return $note === '' ? null : $note;
    }

    /**
     * Hiển thị trang thông báo đặt hàng thành công.
     *
     * @param \App\Models\Order $order
     * @return \Illuminate\View\View
     */
    public function success(Order $order): View
    {
        return view('checkout.success', [
            'order' => $order->load('items'),
            'shippingDistrictLabel' => ShippingFeeCalculator::districtLabel($order->shipping_district),
            'shippingServiceLabel' => ShippingFeeCalculator::serviceLabel($order->shipping_service),
            'deliveryTimeSlotLabel' => DeliveryTimeSlot::label($order->delivery_time_slot),
        ]);
    }

    /**
     * Lấy các mặt hàng trong giỏ hàng.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
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

    /**
     * Tính tổng giá trị giỏ hàng từ danh sách mặt hàng.
     *
     * @param array $items
     * @return float
     */
    private function cartTotal(array $items): float
    {
        return collect($items)->sum('subtotal');
    }
}

