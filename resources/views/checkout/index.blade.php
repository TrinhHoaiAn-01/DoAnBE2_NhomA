@extends('layouts.app', ['title' => 'NeoMart - Đặt hàng'])

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
        <div>
            <p class="text-uppercase text-primary small fw-semibold mb-2">Thanh toán</p>
            <h1 class="h2 fw-bold mb-1">Thông tin đặt hàng</h1>
            <p class="text-secondary mb-0">Nhập địa chỉ, chọn khung giờ giao hàng và hình thức thanh toán.</p>
        </div>
        <a class="btn btn-outline-secondary" href="{{ route('cart.index') }}">Quay lại giỏ hàng</a>
    </div>

    <form class="row g-4" method="post" action="{{ route('checkout.store') }}">
        @csrf
        <div class="col-lg-7">
            <div class="surface rounded-3 p-4">
                <h2 class="h5 fw-bold mb-3">Người nhận</h2>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="customer_name">Họ tên</label>
                        <input class="form-control @error('customer_name') is-invalid @enderror" id="customer_name" name="customer_name" value="{{ old('customer_name', $user?->name) }}" required>
                        @error('customer_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="customer_phone">Số điện thoại</label>
                        <input class="form-control @error('customer_phone') is-invalid @enderror" id="customer_phone" name="customer_phone" value="{{ old('customer_phone', $user?->phone) }}" required>
                        @error('customer_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="customer_email">Email</label>
                        <input class="form-control @error('customer_email') is-invalid @enderror" id="customer_email" name="customer_email" value="{{ old('customer_email', $user?->email) }}">
                        @error('customer_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="shipping_address">Địa chỉ giao hàng</label>
                        <input class="form-control @error('shipping_address') is-invalid @enderror" id="shipping_address" name="shipping_address" value="{{ old('shipping_address') }}" required>
                        @error('shipping_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="shipping_district">Khu vực giao hàng</label>
                        <select class="form-select @error('shipping_district') is-invalid @enderror" id="shipping_district" name="shipping_district" required>
                            @foreach ($shippingDistricts as $value => $district)
                                <option value="{{ $value }}" data-fee="{{ $district['base_fee'] }}" @selected(old('shipping_district', 'noi_thanh') === $value)>
                                    {{ $district['label'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('shipping_district')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="shipping_service">Dịch vụ vận chuyển</label>
                        <select class="form-select @error('shipping_service') is-invalid @enderror" id="shipping_service" name="shipping_service" required>
                            @foreach ($shippingServices as $value => $service)
                                <option value="{{ $value }}" data-fee="{{ $service['extra_fee'] }}" @selected(old('shipping_service', 'standard') === $value)>
                                    {{ $service['label'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('shipping_service')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <h2 class="h5 fw-bold mt-4 mb-3">Lịch giao hàng</h2>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="delivery_date">Ngày giao hàng</label>
                        <input
                            class="form-control @error('delivery_date') is-invalid @enderror"
                            id="delivery_date"
                            name="delivery_date"
                            type="date"
                            min="{{ now()->toDateString() }}"
                            max="{{ now()->addDays(14)->toDateString() }}"
                            value="{{ old('delivery_date', $defaultDeliveryDate) }}"
                            required
                        >
                        @error('delivery_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="delivery_time_slot">Khung giờ nhận hàng</label>
                        <select class="form-select @error('delivery_time_slot') is-invalid @enderror" id="delivery_time_slot" name="delivery_time_slot" required>
                            @foreach ($deliveryTimeSlots as $value => $slot)
                                <option value="{{ $value }}" @selected(old('delivery_time_slot', $defaultDeliveryTimeSlot) === $value)>
                                    {{ $slot['label'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('delivery_time_slot')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <div class="soft-surface rounded-3 p-3 small text-secondary" id="deliverySlotHelp"></div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex justify-content-between gap-3">
                            <label class="form-label" for="note">Ghi chú đơn hàng</label>
                            <span class="small text-secondary" id="noteCounter">0/1000</span>
                        </div>
                        <textarea
                            class="form-control @error('note') is-invalid @enderror"
                            id="note"
                            name="note"
                            rows="3"
                            maxlength="1000"
                            placeholder="Ví dụ: gọi trước khi giao, để hàng ở bảo vệ, không giao giờ nghỉ trưa..."
                        >{{ old('note') }}</textarea>
                        <div class="form-text">NeoMart sẽ chuyển ghi chú này cho nhân viên xử lý đơn.</div>
                        @error('note')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <h2 class="h5 fw-bold mt-4 mb-3">Thanh toán</h2>
                @php
                    $paymentMethods = [
                        'cod' => [
                            'label' => 'Thanh toán khi nhận hàng',
                            'description' => 'Khách kiểm tra đơn và thanh toán trực tiếp cho nhân viên giao hàng.',
                        ],
                        'bank_transfer' => [
                            'label' => 'Chuyển khoản ngân hàng',
                            'description' => 'NeoMart giữ đơn trong 24 giờ để khách chuyển khoản theo mã đơn.',
                        ],
                        'wallet' => [
                            'label' => 'Ví điện tử demo',
                            'description' => 'Mô phỏng thanh toán qua ví điện tử trong môi trường bài tập.',
                        ],
                    ];
                @endphp
                <div class="vstack gap-2">
                    @foreach ($paymentMethods as $value => $method)
                        <label class="soft-surface rounded-3 p-3 d-flex gap-3 align-items-start">
                            <input class="form-check-input mt-1" type="radio" name="payment_method" value="{{ $value }}" @checked(old('payment_method', 'cod') === $value)>
                            <span>
                                <span class="d-block fw-semibold">{{ $method['label'] }}</span>
                                <span class="d-block small text-secondary">{{ $method['description'] }}</span>
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="surface rounded-3 p-4">
                <h2 class="h5 fw-bold mb-3">Đơn hàng</h2>
                <div class="vstack gap-3 mb-4">
                    @foreach ($items as $item)
                        <div class="d-flex justify-content-between gap-3">
                            <div>
                                <div class="fw-semibold">{{ $item['product']->name }}</div>
                                <div class="small text-secondary">SL: {{ $item['quantity'] }}</div>
                            </div>
                            <div class="fw-semibold">{{ number_format($item['subtotal'], 0, ',', '.') }}đ</div>
                        </div>
                    @endforeach
                </div>
                <div class="border-top pt-3">
                    <div class="mb-3">
                        <label class="form-label" for="promotion_code">Mã giảm giá</label>
                        <input class="form-control @error('promotion_code') is-invalid @enderror" id="promotion_code" name="promotion_code" value="{{ old('promotion_code') }}" placeholder="Nhập mã nếu có">
                        @error('promotion_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tạm tính</span>
                        <strong>{{ number_format($subtotal, 0, ',', '.') }}đ</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Phí giao hàng</span>
                        <strong id="shippingFeeText">{{ number_format($shippingFee, 0, ',', '.') }}đ</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Giảm giá</span>
                        <strong>-{{ number_format($discountTotal, 0, ',', '.') }}đ</strong>
                    </div>
                    <div class="d-flex justify-content-between fs-5">
                        <span>Tổng cộng</span>
                        <strong id="orderTotalText">{{ number_format($subtotal + $shippingFee - $discountTotal, 0, ',', '.') }}đ</strong>
                    </div>
                </div>
                <button class="btn btn-primary w-100 mt-4" type="submit">Xác nhận đặt hàng</button>
            </div>
        </div>
    </form>

    <script>
        const subtotal = {{ (float) $subtotal }};
        const districtSelect = document.getElementById('shipping_district');
        const serviceSelect = document.getElementById('shipping_service');
        const shippingFeeText = document.getElementById('shippingFeeText');
        const orderTotalText = document.getElementById('orderTotalText');
        const discountTotal = {{ (float) $discountTotal }};
        const money = new Intl.NumberFormat('vi-VN');
        const slotHelp = document.getElementById('deliverySlotHelp');
        const slotSelect = document.getElementById('delivery_time_slot');
        const slotDescriptions = @json(collect($deliveryTimeSlots)->mapWithKeys(fn ($slot, $value) => [$value => $slot['description']]));
        const noteInput = document.getElementById('note');
        const noteCounter = document.getElementById('noteCounter');

        function updateShippingFee() {
            const districtFee = Number(districtSelect.selectedOptions[0].dataset.fee || 0);
            const serviceFee = Number(serviceSelect.selectedOptions[0].dataset.fee || 0);
            const fee = subtotal >= 500000 && serviceSelect.value === 'standard' ? 0 : districtFee + serviceFee;

            shippingFeeText.textContent = money.format(fee) + 'đ';
            orderTotalText.textContent = money.format(Math.max(0, subtotal + fee - discountTotal)) + 'đ';
        }

        function updateSlotHelp() {
            slotHelp.textContent = slotDescriptions[slotSelect.value] || 'Chọn khung giờ phù hợp để nhân viên giao hàng liên hệ trước khi đến.';
        }

        function updateNoteCounter() {
            noteCounter.textContent = noteInput.value.length + '/1000';
        }

        districtSelect.addEventListener('change', updateShippingFee);
        serviceSelect.addEventListener('change', updateShippingFee);
        slotSelect.addEventListener('change', updateSlotHelp);
        noteInput.addEventListener('input', updateNoteCounter);
        updateShippingFee();
        updateSlotHelp();
        updateNoteCounter();
    </script>
@endsection
