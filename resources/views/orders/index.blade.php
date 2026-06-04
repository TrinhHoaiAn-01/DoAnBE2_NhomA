@extends('layouts.app', ['title' => 'NeoMart - Lịch sử đơn hàng'])

@push('styles')
<style>
/* ===== ORDER HISTORY STYLES ===== */
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
.cart-card {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: var(--radius-lg, 16px);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
}
.cart-table {
    margin-bottom: 0;
}
.cart-table th {
    font-size: 0.78rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--text-muted);
    border-bottom: 2px solid var(--surface-3);
    padding: 1.25rem 1rem;
    background-color: #fafbfc;
}
.cart-table td {
    padding: 1.25rem 1rem;
    vertical-align: middle;
    border-bottom: 1px solid var(--border);
}
.cart-table tr:last-child td {
    border-bottom: none;
}

/* Filter section */
.filter-card {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: var(--radius-lg, 16px);
    padding: 1.25rem 1.5rem;
    margin-bottom: 1.25rem;
    box-shadow: var(--shadow-sm);
}

/* Order code */
.order-code {
    color: var(--primary);
    background: rgba(var(--primary-rgb, 22,163,74), 0.08);
    padding: 3px 8px;
    border-radius: 6px;
    font-family: monospace;
    font-size: 0.82rem;
    font-weight: 600;
}

/* Status badges */
.badge-order-status {
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    display: inline-block;
}
.badge-pending { background: #fff7ed; color: #c2410c; border: 1px solid #fed7aa; }
.badge-confirmed { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
.badge-shipping { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
.badge-delivered { background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; }
.badge-cancelled { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }
.badge-default { background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0; }

/* Mobile card list */
.mobile-order-list { display: none; }
.mobile-order-item {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: var(--radius-md, 10px);
    padding: 1.25rem;
    margin-bottom: 1rem;
    box-shadow: var(--shadow-sm);
}
.mobile-order-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.75rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid var(--border);
}
.mobile-order-body {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem 1.5rem;
    margin-bottom: 0.75rem;
}
.mobile-order-field label {
    font-size: 0.7rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--text-muted);
    font-weight: 700;
    display: block;
    margin-bottom: 0.15rem;
}
.mobile-order-field span {
    font-size: 0.88rem;
    font-weight: 600;
    color: var(--text-primary);
}
.mobile-order-actions {
    display: flex;
    gap: 0.5rem;
    justify-content: flex-end;
    padding-top: 0.75rem;
    border-top: 1px solid var(--border);
}

@media (max-width: 767.98px) {
    .desktop-order-table { display: none; }
    .mobile-order-list { display: block; }
}
</style>
@endpush

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4 mt-2">
        <div>
            <p class="text-uppercase text-primary small fw-extrabold mb-1" style="letter-spacing: 1px;">TÀI KHOẢN</p>
            <h1 class="cart-header-title mb-0">Lịch sử đơn hàng</h1>
            <p class="text-secondary small mb-0">Theo dõi các đơn đã đặt, trạng thái xử lý và thông tin giao hàng.</p>
        </div>
        <a class="btn btn-outline-success btn-continue-shopping px-4" href="{{ route('products.index') }}">
            <i class="bi bi-grid me-1"></i> Tiếp tục mua hàng
        </a>
    </div>

    @if(session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 12px;">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 12px;">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Filter --}}
    <div class="filter-card">
        <form class="row g-3 align-items-end" method="get" action="{{ route('orders.index') }}">
            <div class="col-md-5 col-lg-4">
                <label class="form-label small fw-bold text-secondary" for="status">Lọc theo trạng thái</label>
                <select class="form-select" id="status" name="status">
                    <option value="">Tất cả đơn hàng</option>
                    @foreach ($statusOptions as $value => $label)
                        <option value="{{ $value }}" @selected($status === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-auto d-flex gap-2">
                <button class="btn btn-success fw-bold px-4" type="submit" style="border-radius: 10px;">
                    <i class="bi bi-funnel me-1"></i> Lọc đơn
                </button>
                <a class="btn btn-outline-secondary px-3" href="{{ route('orders.index') }}" style="border-radius: 10px;">Xóa lọc</a>
            </div>
        </form>
    </div>

    {{-- Desktop Table --}}
    <div class="cart-card desktop-order-table">
        @if ($orders->count() > 0)
            <table class="table cart-table align-middle">
                <thead>
                    <tr>
                        <th>MÃ ĐƠN</th>
                        <th>NGÀY ĐẶT</th>
                        <th>SẢN PHẨM</th>
                        <th>TỔNG TIỀN</th>
                        <th>TRẠNG THÁI</th>
                        <th class="text-end">THAO TÁC</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <td>
                                <span class="order-code">{{ $order->code }}</span>
                                <div class="small text-secondary mt-1">{{ $order->payment_method === 'cod' ? 'COD' : 'Thanh toán demo' }}</div>
                            </td>
                            <td>{{ $order->created_at?->format('d/m/Y H:i') }}</td>
                            <td>{{ $order->items_count }} sản phẩm</td>
                            <td class="fw-bold text-danger">{{ number_format((float) $order->total, 0, ',', '.') }}đ</td>
                            <td>
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
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-2">
                                    <a class="btn btn-sm btn-outline-primary px-3" style="border-radius: 8px;" href="{{ route('orders.show', $order) }}">
                                        Theo dõi
                                    </a>
                                    @if (\App\Support\OrderStatus::canBeCancelled($order->status))
                                        <form method="post" action="{{ route('orders.cancel', $order) }}" onsubmit="return confirm('Bạn chắc chắn muốn hủy đơn hàng này?');" class="m-0">
                                            @csrf
                                            @method('patch')
                                            <button class="btn btn-sm btn-outline-danger px-3" style="border-radius: 8px;" type="submit">
                                                Hủy
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="p-5 text-center">
                <div class="mb-3 text-muted">
                    <i class="bi bi-receipt" style="font-size: 4rem;"></i>
                </div>
                <h5 class="fw-bold mb-2">Chưa có đơn hàng</h5>
                <p class="text-muted small mb-4">Các đơn hàng đã đặt sẽ xuất hiện tại đây để bạn tiện theo dõi.</p>
                <a class="btn btn-success px-4 fw-bold" href="{{ route('products.index') }}" style="border-radius: 10px;">
                    Xem sản phẩm
                </a>
            </div>
        @endif
    </div>

    {{-- Mobile Card List --}}
    @if ($orders->count() > 0)
        <div class="mobile-order-list">
            @foreach ($orders as $order)
                <div class="mobile-order-item">
                    <div class="mobile-order-header">
                        <span class="order-code">{{ $order->code }}</span>
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
                    <div class="mobile-order-body">
                        <div class="mobile-order-field">
                            <label>Ngày đặt</label>
                            <span>{{ $order->created_at?->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="mobile-order-field">
                            <label>Sản phẩm</label>
                            <span>{{ $order->items_count }} sản phẩm</span>
                        </div>
                        <div class="mobile-order-field">
                            <label>Tổng tiền</label>
                            <span class="text-danger">{{ number_format((float) $order->total, 0, ',', '.') }}đ</span>
                        </div>
                        <div class="mobile-order-field">
                            <label>Thanh toán</label>
                            <span>{{ $order->payment_method === 'cod' ? 'COD' : 'Online' }}</span>
                        </div>
                    </div>
                    <div class="mobile-order-actions">
                        <a class="btn btn-sm btn-outline-primary px-3" style="border-radius: 8px;" href="{{ route('orders.show', $order) }}">
                            Theo dõi
                        </a>
                        @if (\App\Support\OrderStatus::canBeCancelled($order->status))
                            <form method="post" action="{{ route('orders.cancel', $order) }}" onsubmit="return confirm('Bạn chắc chắn muốn hủy đơn hàng này?');" class="m-0">
                                @csrf
                                @method('patch')
                                <button class="btn btn-sm btn-outline-danger px-3" style="border-radius: 8px;" type="submit">Hủy</button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    @if ($orders->hasPages())
        <div class="mt-4">{{ $orders->links() }}</div>
    @endif
@endsection
