@extends('layouts.app', ['title' => 'NeoMart - Theo dõi đơn hàng'])

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
        <div>
            <p class="text-uppercase text-primary small fw-semibold mb-2">Theo dõi đơn hàng</p>
            <h1 class="h2 fw-bold mb-1">{{ $order->code }}</h1>
            <p class="text-secondary mb-0">Đặt lúc {{ $order->created_at?->format('d/m/Y H:i') }}</p>
        </div>
        <a class="btn btn-outline-secondary" href="{{ route('orders.index') }}">
            <i class="bi bi-arrow-left me-1"></i>Quay lại
        </a>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="surface rounded-3 p-4 mb-4">
                <h2 class="h5 fw-bold mb-3">Tiến trình đơn hàng</h2>
                <div class="row g-3">
                    @foreach ($trackingSteps as $step)
                        @php
                            $isDone = $step['state'] === 'done';
                            $isActive = $step['state'] === 'active';
                            $isDanger = $step['state'] === 'danger';
                            $iconClass = $isDanger ? 'text-bg-danger' : ($isDone ? 'text-bg-success' : ($isActive ? 'text-bg-primary' : 'text-bg-light text-secondary border'));
                        @endphp
                        <div class="col-md-6">
                            <div class="soft-surface rounded-3 p-3 h-100">
                                <div class="d-flex gap-3 align-items-start">
                                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center {{ $iconClass }}" style="width: 42px; height: 42px;">
                                        <i class="bi {{ $step['icon'] }}"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $step['label'] }}</div>
                                        <div class="small text-secondary">{{ $step['description'] }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="surface rounded-3 p-4 mb-4">
                <div class="d-flex flex-wrap justify-content-between gap-3 mb-3">
                    <div>
                        <h2 class="h5 fw-bold mb-1">Sản phẩm đã đặt</h2>
                        <p class="text-secondary mb-0">Danh sách sản phẩm trong đơn hàng.</p>
                    </div>
                    <span class="badge text-bg-light border align-self-start">
                        {{ $statusOptions[$order->status] ?? $order->status }}
                    </span>
                </div>

                <div class="vstack gap-3">
                    @foreach ($order->items as $item)
                        <div class="d-flex gap-3 align-items-center border-bottom pb-3">
                            <img
                                class="rounded border"
                                src="{{ $item->product?->image_url ?: 'https://placehold.co/96x96?text=NeoMart' }}"
                                alt="{{ $item->product_name }}"
                                width="72"
                                height="72"
                                style="object-fit: cover"
                            >
                            <div class="flex-grow-1">
                                <div class="fw-semibold">{{ $item->product_name }}</div>
                                <div class="small text-secondary">SKU: {{ $item->sku ?: 'Đang cập nhật' }}</div>
                                <div class="small text-secondary">Số lượng: {{ $item->quantity }}</div>
                            </div>
                            <div class="fw-semibold text-end">
                                {{ number_format((float) $item->subtotal, 0, ',', '.') }}đ
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="surface rounded-3 p-4 mb-4">
                <h2 class="h5 fw-bold mb-3">Thông tin nhận hàng</h2>
                <div class="vstack gap-3">
                    <div>
                        <div class="small text-secondary">Người nhận</div>
                        <div class="fw-semibold">{{ $order->customer_name }}</div>
                    </div>
                    <div>
                        <div class="small text-secondary">Số điện thoại</div>
                        <div class="fw-semibold">{{ $order->customer_phone }}</div>
                    </div>
                    <div>
                        <div class="small text-secondary">Địa chỉ</div>
                        <div>{{ $order->shipping_address }}</div>
                    </div>
                    <div>
                        <div class="small text-secondary">Vận chuyển</div>
                        <div>{{ $shippingDistrictLabel }} - {{ $shippingServiceLabel }}</div>
                    </div>
                    <div>
                        <div class="small text-secondary">Lịch giao</div>
                        <div>{{ $order->delivery_date?->format('d/m/Y') ?: 'Chưa chọn ngày' }} - {{ $deliveryTimeSlotLabel }}</div>
                    </div>
                </div>
            </div>

            <div class="surface rounded-3 p-4">
                <h2 class="h5 fw-bold mb-3">Thanh toán</h2>
                <div class="d-flex justify-content-between mb-2">
                    <span>Tạm tính</span>
                    <strong>{{ number_format((float) $order->subtotal, 0, ',', '.') }}đ</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Phí giao hàng</span>
                    <strong>{{ number_format((float) $order->shipping_fee, 0, ',', '.') }}đ</strong>
                </div>
                @if ((float) $order->discount_total > 0)
                    <div class="d-flex justify-content-between mb-2 text-success">
                        <span>Giảm giá</span>
                        <strong>-{{ number_format((float) $order->discount_total, 0, ',', '.') }}đ</strong>
                    </div>
                @endif
                <div class="border-top pt-3 d-flex justify-content-between fs-5">
                    <span>Tổng cộng</span>
                    <strong>{{ number_format((float) $order->total, 0, ',', '.') }}đ</strong>
                </div>
            </div>
        </div>
    </div>
@endsection
