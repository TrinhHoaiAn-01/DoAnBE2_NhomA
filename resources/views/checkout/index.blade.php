@extends('layouts.app', ['title' => 'NeoMart - Dat hang'])

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
        <div>
            <p class="text-uppercase text-primary small fw-semibold mb-2">Thanh toan</p>
            <h1 class="h2 fw-bold mb-1">Thong tin dat hang</h1>
            <p class="text-secondary mb-0">Nhap dia chi giao hang va chon hinh thuc thanh toan.</p>
        </div>
        <a class="btn btn-outline-secondary" href="{{ route('cart.index') }}">Quay lai gio hang</a>
    </div>

    <form class="row g-4" method="post" action="{{ route('checkout.store') }}">
        @csrf
        <div class="col-lg-7">
            <div class="surface rounded-3 p-4">
                <h2 class="h5 fw-bold mb-3">Nguoi nhan</h2>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="customer_name">Ho ten</label>
                        <input class="form-control @error('customer_name') is-invalid @enderror" id="customer_name" name="customer_name" value="{{ old('customer_name', $user?->name) }}" required>
                        @error('customer_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="customer_phone">So dien thoai</label>
                        <input class="form-control @error('customer_phone') is-invalid @enderror" id="customer_phone" name="customer_phone" value="{{ old('customer_phone', $user?->phone) }}" required>
                        @error('customer_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="customer_email">Email</label>
                        <input class="form-control @error('customer_email') is-invalid @enderror" id="customer_email" name="customer_email" value="{{ old('customer_email', $user?->email) }}">
                        @error('customer_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="shipping_address">Dia chi giao hang</label>
                        <input class="form-control @error('shipping_address') is-invalid @enderror" id="shipping_address" name="shipping_address" value="{{ old('shipping_address') }}" required>
                        @error('shipping_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="shipping_district">Khu vá»±c giao hÃ ng</label>
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
                        <label class="form-label" for="shipping_service">Dá»‹ch vá»¥ váº­n chuyá»ƒn</label>
                        <select class="form-select @error('shipping_service') is-invalid @enderror" id="shipping_service" name="shipping_service" required>
                            @foreach ($shippingServices as $value => $service)
                                <option value="{{ $value }}" data-fee="{{ $service['extra_fee'] }}" @selected(old('shipping_service', 'standard') === $value)>
                                    {{ $service['label'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('shipping_service')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="note">Ghi chu</label>
                        <textarea class="form-control @error('note') is-invalid @enderror" id="note" name="note" rows="3">{{ old('note') }}</textarea>
                        @error('note')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <h2 class="h5 fw-bold mt-4 mb-3">Thanh toan</h2>
                @php
                    $paymentMethods = [
                        'cod' => [
                            'label' => 'Thanh toan khi nhan hang',
                            'description' => 'Khach kiem tra don va thanh toan truc tiep cho nhan vien giao hang.',
                        ],
                        'bank_transfer' => [
                            'label' => 'Chuyen khoan ngan hang',
                            'description' => 'NeoMart giu don trong 24 gio de khach chuyen khoan theo ma don.',
                        ],
                        'wallet' => [
                            'label' => 'Vi dien tu demo',
                            'description' => 'Mo phong thanh toan qua vi dien tu trong moi truong bai tap.',
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
                <h2 class="h5 fw-bold mb-3">Don hang</h2>
                <div class="vstack gap-3 mb-4">
                    @foreach ($items as $item)
                        <div class="d-flex justify-content-between gap-3">
                            <div>
                                <div class="fw-semibold">{{ $item['product']->name }}</div>
                                <div class="small text-secondary">SL: {{ $item['quantity'] }}</div>
                            </div>
                            <div class="fw-semibold">{{ number_format($item['subtotal'], 0, ',', '.') }}d</div>
                        </div>
                    @endforeach
                </div>
                <div class="border-top pt-3">
                    <div class="mb-3">
                        <label class="form-label" for="promotion_code">Ma giam gia</label>
                        <input class="form-control @error('promotion_code') is-invalid @enderror" id="promotion_code" name="promotion_code" value="{{ old('promotion_code') }}" placeholder="Nhap ma neu co">
                        @error('promotion_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tam tinh</span>
                        <strong>{{ number_format($subtotal, 0, ',', '.') }}d</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Phi giao hang</span>
                        <strong id="shippingFeeText">{{ number_format($shippingFee, 0, ',', '.') }}d</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Giam gia</span>
                        <strong>-{{ number_format($discountTotal, 0, ',', '.') }}d</strong>
                    </div>
                    <div class="d-flex justify-content-between fs-5">
                        <span>Tong cong</span>
                        <strong id="orderTotalText">{{ number_format($subtotal + $shippingFee - $discountTotal, 0, ',', '.') }}d</strong>
                    </div>
                </div>
                <button class="btn btn-primary w-100 mt-4" type="submit">Xac nhan dat hang</button>
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

        function updateShippingFee() {
            const districtFee = Number(districtSelect.selectedOptions[0].dataset.fee || 0);
            const serviceFee = Number(serviceSelect.selectedOptions[0].dataset.fee || 0);
            const fee = subtotal >= 500000 && serviceSelect.value === 'standard' ? 0 : districtFee + serviceFee;

            shippingFeeText.textContent = money.format(fee) + 'd';
            orderTotalText.textContent = money.format(Math.max(0, subtotal + fee - discountTotal)) + 'd';
        }

        districtSelect.addEventListener('change', updateShippingFee);
        serviceSelect.addEventListener('change', updateShippingFee);
        updateShippingFee();
    </script>
@endsection



