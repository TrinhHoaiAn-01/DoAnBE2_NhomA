@extends('layouts.app', ['title' => 'NeoMart - Theo dõi đơn hàng'])

@push('styles')
<style>
/* ===== ORDER DETAIL STYLES ===== */
.cart-header-title {
    font-size: 1.8rem;
    font-weight: 800;
    color: var(--text-primary);
}
.btn-continue-shopping {
    border-radius: var(--radius-md, 10px);
    font-weight: 600;
    transition: var(--transition);
}

/* Cards */
.detail-card {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: var(--radius-lg, 16px);
    padding: 1.5rem;
    margin-bottom: 1.25rem;
    box-shadow: var(--shadow-sm);
}
.section-title {
    font-size: 1rem;
    font-weight: 700;
    margin-bottom: 1rem;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid var(--surface-3, #e2e8f0);
    color: var(--text-primary);
}

/* Status badges */
.badge-order-status {
    padding: 5px 14px;
    border-radius: 20px;
    font-size: 0.78rem;
    font-weight: 600;
    display: inline-block;
}
.badge-pending { background: #fff7ed; color: #c2410c; border: 1px solid #fed7aa; }
.badge-confirmed { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
.badge-shipping { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
.badge-delivered { background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; }
.badge-cancelled { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }
.badge-default { background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0; }

/* Tracking */
.tracking-step {
    background: #f8fafc;
    border: 1px solid var(--border);
    border-radius: var(--radius-md, 10px);
    padding: 1rem;
    height: 100%;
    transition: var(--transition);
}
.tracking-step:hover {
    box-shadow: var(--shadow-sm);
}
.icon-circle {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    flex-shrink: 0;
}
.step-done { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
.step-active { background: #dbeafe; color: #1d4ed8; border: 1px solid #bfdbfe; }
.step-pending { background: #f1f5f9; color: #94a3b8; border: 1px solid #e2e8f0; }
.step-danger { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }

/* Product items */
.order-product-item {
    display: flex;
    align-items: center;
    padding: 1rem 0;
    border-bottom: 1px solid var(--border);
    gap: 1rem;
}
.order-product-item:last-child {
    border-bottom: none;
    padding-bottom: 0;
}
.order-product-img {
    width: 64px;
    height: 64px;
    object-fit: contain;
    border-radius: var(--radius-md, 10px);
    background: #f8fafc;
    border: 1px solid var(--border);
    padding: 4px;
    flex-shrink: 0;
}

/* Summary row */
.summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.4rem 0;
}
.summary-total {
    font-size: 1.15rem;
    font-weight: 800;
    color: var(--danger, #dc2626);
}

/* Info table */
.info-table td {
    padding: 0.4rem 0;
    vertical-align: top;
}
.info-table .label-col {
    color: var(--text-muted);
    font-size: 0.85rem;
    width: 35%;
    white-space: nowrap;
    padding-right: 1rem;
}
.info-table .value-col {
    font-weight: 600;
    color: var(--text-primary);
    font-size: 0.88rem;
}
</style>
@endpush

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4 mt-2">
        <div>
            <p class="text-uppercase text-primary small fw-extrabold mb-1" style="letter-spacing: 1px;">THEO DÕI ĐƠN HÀNG</p>
            <h1 class="cart-header-title mb-1">{{ $order->code }}</h1>
            <p class="text-secondary small mb-0">Đặt lúc {{ $order->created_at?->format('d/m/Y H:i') }}</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            @if ($canCancel)
                <form method="post" action="{{ route('orders.cancel', $order) }}" onsubmit="return confirm('Bạn chắc chắn muốn hủy đơn hàng này?');" class="m-0">
                    @csrf
                    @method('patch')
                    <button class="btn btn-outline-danger btn-continue-shopping px-4" type="submit">
                        <i class="bi bi-x-circle me-1"></i> Hủy đơn
                    </button>
                </form>
            @endif
            <a class="btn btn-outline-success btn-continue-shopping px-4" href="{{ route('orders.index') }}">
                <i class="bi bi-arrow-left me-1"></i> Quay lại
            </a>
        </div>
    </div>

    @if(session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 12px;">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        {{-- Tracking --}}
        <div class="col-12">
            <div class="detail-card">
                <h2 class="section-title"><i class="bi bi-signpost-2 me-2"></i>Tiến trình đơn hàng</h2>
                <div class="row g-3">
                    @foreach ($trackingSteps as $step)
                        @php
                            $isDone = $step['state'] === 'done';
                            $isActive = $step['state'] === 'active';
                            $isDanger = $step['state'] === 'danger';
                            $stepClass = $isDanger ? 'step-danger' : ($isDone ? 'step-done' : ($isActive ? 'step-active' : 'step-pending'));
                        @endphp
                        <div class="col-md-6 col-lg-3">
                            <div class="tracking-step d-flex gap-3 align-items-center">
                                <div class="icon-circle {{ $stepClass }}">
                                    <i class="bi {{ $step['icon'] }}"></i>
                                </div>
                                <div>
                                    <div class="fw-bold" style="font-size: 0.88rem;">{{ $step['label'] }}</div>
                                    <div class="small text-muted">{{ $step['description'] }}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Products --}}
        <div class="col-lg-8">
            <div class="detail-card">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-0">
                    <h2 class="section-title mb-0 border-0 pb-0"><i class="bi bi-box-seam me-2"></i>Sản phẩm đã đặt</h2>
                    @php
                        $statusClass = match($order->status) {
                            'pending' => 'badge-pending',
                            'confirmed' => 'badge-confirmed',
                            'shipping' => 'badge-shipping',
                            'delivered' => 'badge-delivered',
                            'cancelled' => 'badge-cancelled',
                            default => 'badge-default',
                        };
                    @endphp
                    <span class="badge-order-status {{ $statusClass }}">{{ $statusOptions[$order->status] ?? $order->status }}</span>
                </div>
                <hr style="border-color: var(--border); margin: 0.75rem 0;">
                @foreach ($order->items as $item)
                    <div class="order-product-item">
                        <img class="order-product-img"
                            src="{{ $item->product?->image_url ?: 'https://placehold.co/96x96?text=NeoMart' }}"
                            alt="{{ $item->product_name }}">
                        <div class="flex-grow-1">
                            <div class="fw-bold" style="font-size: 0.92rem;">{{ $item->product_name }}</div>
                            <div class="small text-muted">SKU: {{ $item->sku ?: 'Đang cập nhật' }} &nbsp;·&nbsp; SL: {{ $item->quantity }}</div>
                        </div>
                        <div class="fw-bold text-end text-danger">
                            {{ number_format((float) $item->subtotal, 0, ',', '.') }}đ
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="col-lg-4">
            {{-- Shipping info --}}
            <div class="detail-card">
                <h2 class="section-title"><i class="bi bi-truck me-2"></i>Thông tin nhận hàng</h2>
                <table class="info-table" style="width: 100%; border-collapse: collapse;">
                    <tbody>
                        <tr>
                            <td class="label-col">Người nhận:</td>
                            <td class="value-col">{{ $order->customer_name }}</td>
                        </tr>
                        <tr>
                            <td class="label-col">Điện thoại:</td>
                            <td class="value-col">{{ $order->customer_phone }}</td>
                        </tr>
                        <tr>
                            <td class="label-col">Địa chỉ:</td>
                            <td class="value-col">{{ $order->shipping_address }}</td>
                        </tr>
                        <tr>
                            <td class="label-col">Vận chuyển:</td>
                            <td class="value-col">{{ $shippingDistrictLabel }} - {{ $shippingServiceLabel }}</td>
                        </tr>
                        <tr>
                            <td class="label-col">Lịch giao:</td>
                            <td class="value-col">{{ $order->delivery_date?->format('d/m/Y') ?: 'Chưa chọn ngày' }} - {{ $deliveryTimeSlotLabel }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Payment --}}
            <div class="detail-card">
                <h2 class="section-title"><i class="bi bi-credit-card me-2"></i>Thanh toán</h2>
                <div class="summary-row">
                    <span class="text-muted">Tạm tính</span>
                    <strong>{{ number_format((float) $order->subtotal, 0, ',', '.') }}đ</strong>
                </div>
                <div class="summary-row">
                    <span class="text-muted">Phí giao hàng</span>
                    <strong>{{ number_format((float) $order->shipping_fee, 0, ',', '.') }}đ</strong>
                </div>
                @if ((float) $order->discount_total > 0)
                    <div class="summary-row text-success">
                        <span>Giảm giá</span>
                        <strong>-{{ number_format((float) $order->discount_total, 0, ',', '.') }}đ</strong>
                    </div>
                @endif
                <hr style="border-color: var(--border);">
                <div class="summary-row">
                    <span class="fw-bold">Tổng cộng</span>
                    <span class="summary-total">{{ number_format((float) $order->total, 0, ',', '.') }}đ</span>
                </div>
            </div>
        </div>
    </div>
@endsection
