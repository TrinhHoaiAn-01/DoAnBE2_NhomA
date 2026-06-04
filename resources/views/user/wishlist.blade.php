@extends('layouts.app', ['title' => 'Sản phẩm yêu thích – NeoMart'])

@push('styles')
<style>
/* ===== WISHLIST PAGE STYLES ===== */
.wishlist-header-title {
    font-size: 1.8rem;
    font-weight: 800;
    color: var(--text-primary);
}
.btn-continue-shopping {
    border-radius: var(--radius-md, 10px);
    font-weight: 600;
    transition: var(--transition);
}
.wishlist-card {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: var(--radius-lg, 16px);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
}
.wishlist-table {
    margin-bottom: 0;
}
.wishlist-table th {
    font-size: 0.78rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--text-muted);
    border-bottom: 2px solid var(--surface-3);
    padding: 1.25rem 1rem;
    background-color: #fafbfc;
}
.wishlist-table td {
    padding: 1.5rem 1rem;
    vertical-align: middle;
    border-bottom: 1px solid var(--border);
}
.wishlist-table tr:last-child td {
    border-bottom: none;
}
.wishlist-product-img {
    width: 68px;
    height: 68px;
    object-fit: contain;
    background: #f8fafc;
    border: 1px solid var(--border);
    border-radius: var(--radius-md, 10px);
    padding: 4px;
}
.wishlist-product-name {
    font-weight: 700;
    font-size: 0.95rem;
    color: var(--text-primary);
    text-decoration: none;
    line-height: 1.4;
    display: block;
    margin-bottom: 0.2rem;
    transition: color 0.2s;
}
.wishlist-product-name:hover {
    color: var(--primary);
}
.wishlist-product-cat {
    font-size: 0.75rem;
    color: var(--text-muted);
    font-weight: 600;
    text-transform: uppercase;
}
.wishlist-unit-price {
    font-weight: 700;
    color: var(--danger);
    font-size: 1.05rem;
}

/* Sidebar Info Card */
.summary-card {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: var(--radius-lg, 16px);
    padding: 1.75rem;
    position: sticky;
    top: 90px;
    box-shadow: var(--shadow-sm);
}
.summary-title {
    font-weight: 800;
    font-size: 1.25rem;
    color: var(--text-primary);
    margin-bottom: 1.5rem;
    border-bottom: 1px solid var(--surface-3);
    padding-bottom: 0.75rem;
}
.summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    font-size: 0.95rem;
}
.summary-row.total-row {
    border-top: 1px solid var(--surface-3);
    margin-top: 1.25rem;
    padding-top: 1.25rem;
    margin-bottom: 0.25rem;
}
.summary-label {
    color: var(--text-secondary);
    font-weight: 500;
}
.summary-value {
    color: var(--text-primary);
    font-weight: 700;
}
.summary-total-label {
    font-weight: 800;
    font-size: 1.1rem;
    color: var(--text-primary);
}
.btn-checkout-confirm {
    background: var(--primary);
    color: #fff;
    border: none;
    border-radius: var(--radius-md, 10px);
    padding: 0.85rem 1.5rem;
    font-weight: 700;
    font-size: 1.05rem;
    transition: var(--transition);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    width: 100%;
    text-decoration: none;
}
.btn-checkout-confirm:hover {
    background: var(--primary-dark);
    box-shadow: 0 8px 20px rgba(0, 136, 72, 0.25);
    transform: translateY(-2px);
    color: #fff;
}

/* Mobile view styling */
.desktop-wishlist-table {
    display: block;
}
.mobile-wishlist-list {
    display: none;
}
.mobile-wishlist-item {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: var(--radius-md, 10px);
    padding: 1.25rem 1rem;
    margin-bottom: 1rem;
    display: flex;
    gap: 1rem;
    position: relative;
    box-shadow: var(--shadow-sm);
}
.mobile-wishlist-img {
    width: 76px;
    height: 76px;
    object-fit: contain;
    background: #f8fafc;
    border: 1px solid var(--border);
    border-radius: var(--radius-md, 10px);
    flex-shrink: 0;
    padding: 4px;
}
.mobile-wishlist-details {
    flex: 1;
    display: flex;
    flex-direction: column;
}
.mobile-wishlist-name {
    font-weight: 700;
    font-size: 0.9rem;
    color: var(--text-primary);
    text-decoration: none;
    line-height: 1.35;
    margin-bottom: 0.25rem;
    padding-right: 1.5rem;
}
.mobile-wishlist-cat {
    font-size: 0.72rem;
    color: var(--text-muted);
    font-weight: 600;
    text-transform: uppercase;
    margin-bottom: 0.4rem;
}
.mobile-wishlist-price {
    font-weight: 700;
    color: var(--danger);
    font-size: 1rem;
    margin-bottom: 0.5rem;
}
.mobile-wishlist-delete {
    position: absolute;
    top: 12px;
    right: 12px;
    color: var(--text-muted);
    border: none;
    background: transparent;
    cursor: pointer;
    transition: var(--transition);
}
.mobile-wishlist-delete:hover {
    color: var(--danger);
}

@media (max-width: 767.98px) {
    .desktop-wishlist-table {
        display: none;
    }
    .mobile-wishlist-list {
        display: block;
    }
}
</style>
@endpush

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4 mt-2">
        <div>
            <p class="text-uppercase text-primary small fw-extrabold mb-1" style="letter-spacing: 1px;">SẢN PHẨM YÊU THÍCH</p>
            <h1 class="wishlist-header-title mb-0">Danh sách yêu thích của bạn</h1>
            <p class="text-secondary small mb-0">Xem và quản lý các sản phẩm bạn đã thêm vào danh sách yêu thích.</p>
        </div>
        <a class="btn btn-outline-success btn-continue-shopping px-4" href="{{ route('products.index') }}">
            <i class="bi bi-arrow-left me-2"></i> Tiếp tục mua sắm
        </a>
    </div>

    @if ($wishlists->count() > 0)
        <div class="row g-4">
            {{-- Left column - Product list --}}
            <div class="col-lg-8">
                {{-- Desktop Table View --}}
                <div class="wishlist-card desktop-wishlist-table">
                    <table class="table wishlist-table align-middle">
                        <thead>
                            <tr>
                                <th>SẢN PHẨM</th>
                                <th>ĐƠN GIÁ</th>
                                <th>TRẠNG THÁI</th>
                                <th style="text-align: center;">THAO TÁC</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($wishlists as $item)
                                @if($item->product)
                                    <tr id="wishlist-row-{{ $item->id }}">
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <img class="wishlist-product-img" src="{{ $item->product->image_url ?: 'https://placehold.co/100?text='.urlencode($item->product->name) }}" alt="{{ $item->product->name }}">
                                                <div>
                                                    <a href="{{ route('products.show', $item->product) }}" class="wishlist-product-name">{{ $item->product->name }}</a>
                                                    <span class="wishlist-product-cat">{{ $item->product->category?->name }}</span>
                                                    @if($item->product->stock <= 3 && $item->product->stock > 0)
                                                        <div class="text-warning small mt-1 fw-bold">
                                                            ⚠️ Chỉ còn {{ $item->product->stock }} sản phẩm!
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="wishlist-unit-price">{{ number_format((float) $item->product->price, 0, ',', '.') }}đ</span>
                                        </td>
                                        <td>
                                            @if($item->product->stock <= 0)
                                                <span class="badge bg-danger bg-opacity-10 text-danger fw-bold" style="font-size: 0.75rem;">Hết hàng</span>
                                            @elseif($item->product->stock <= 3)
                                                <span class="badge bg-warning bg-opacity-10 text-warning fw-bold" style="font-size: 0.75rem; color: #d97706 !important;">Sắp hết hàng</span>
                                            @else
                                                <span class="badge bg-success bg-opacity-10 text-success fw-bold" style="font-size: 0.75rem;">Còn hàng</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-center gap-3">
                                                <form method="post" action="{{ route('cart.add', $item->product) }}" class="m-0">
                                                    @csrf
                                                    <button class="btn btn-success fw-bold px-3 py-1.5" type="submit" @disabled($item->product->stock <= 0) style="border-radius: 8px; font-size: 0.85rem;">
                                                        THÊM VÀO GIỎ
                                                    </button>
                                                </form>
                                                <form method="POST" action="{{ url('/wishlist/'.$item->id) }}" class="m-0 wishlist-delete-form" data-id="{{ $item->id }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-link text-muted p-1" type="submit" title="Xóa khỏi danh sách yêu thích">
                                                        <i class="bi bi-trash fs-5"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Mobile Card List View --}}
                <div class="mobile-wishlist-list">
                    @foreach ($wishlists as $item)
                        @if($item->product)
                            <div class="mobile-wishlist-item" id="mobile-wishlist-item-{{ $item->id }}">
                                <img class="mobile-wishlist-img" src="{{ $item->product->image_url ?: 'https://placehold.co/100?text='.urlencode($item->product->name) }}" alt="{{ $item->product->name }}">
                                <div class="mobile-wishlist-details">
                                    <a href="{{ route('products.show', $item->product) }}" class="mobile-wishlist-name">{{ $item->product->name }}</a>
                                    <span class="mobile-wishlist-cat">{{ $item->product->category?->name }}</span>
                                    
                                    @if($item->product->stock <= 3 && $item->product->stock > 0)
                                        <div class="text-warning small mb-1 fw-bold" style="font-size: 0.72rem;">
                                            ⚠️ Chỉ còn {{ $item->product->stock }} sản phẩm!
                                        </div>
                                    @endif
                                    
                                    <span class="mobile-wishlist-price">{{ number_format((float) $item->product->price, 0, ',', '.') }}đ</span>
                                    
                                    <div class="d-flex align-items-center justify-content-between mt-2">
                                        @if($item->product->stock <= 0)
                                            <span class="badge bg-danger bg-opacity-10 text-danger fw-bold" style="font-size: 0.7rem; padding: 0.25rem 0.5rem;">Hết hàng</span>
                                        @else
                                            <form method="post" action="{{ route('cart.add', $item->product) }}" class="m-0">
                                                @csrf
                                                <button class="btn btn-sm btn-success fw-bold py-1 px-3" type="submit" style="border-radius: 8px; font-size: 0.75rem;">
                                                    THÊM VÀO GIỎ
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                                
                                <form method="POST" action="{{ url('/wishlist/'.$item->id) }}" class="m-0 wishlist-delete-form" data-id="{{ $item->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="mobile-wishlist-delete" title="Xóa">
                                        <i class="bi bi-trash fs-5"></i>
                                    </button>
                                </form>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            {{-- Right column - Summary card --}}
            <div class="col-lg-4">
                <div class="summary-card">
                    <h2 class="summary-title">Thông tin danh sách</h2>
                    
                    <div class="summary-row">
                        <span class="summary-label">Số sản phẩm yêu thích</span>
                        <span class="summary-value" id="wishlist-count-summary">{{ $wishlists->count() }}</span>
                    </div>
                    
                    <div class="summary-row total-row">
                        <span class="summary-total-label">Trạng thái</span>
                        <div class="text-end">
                            <span class="badge bg-success bg-opacity-10 text-success fw-bold" style="font-size: 0.85rem; padding: 0.35rem 0.75rem;">Đang lưu trữ</span>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <a class="btn-checkout-confirm" href="{{ route('products.index') }}">
                            Khám phá thêm sản phẩm <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="wishlist-card p-5 text-center">
            <div class="py-4">
                <div class="mb-3 text-muted">
                    <i class="bi bi-heart" style="font-size: 4rem; color: #cbd5e1;"></i>
                </div>
                <h5 class="fw-bold mb-2">Danh sách yêu thích trống</h5>
                <p class="text-muted small mb-4">Hãy thêm những sản phẩm bạn yêu thích để theo dõi tại đây.</p>
                <a class="btn btn-success px-4 fw-bold" href="{{ route('products.index') }}" style="border-radius: 10px;">
                    Khám phá sản phẩm
                </a>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.wishlist-delete-form').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                if (!confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi danh sách yêu thích?')) {
                    return;
                }

                const wishlistId = this.dataset.id;
                const formData = new FormData(this);

                fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(function(res) {
                    if (!res.ok) {
                        throw new Error('Lỗi từ hệ thống (Status ' + res.status + ')');
                    }
                    return res.json();
                })
                .then(function(data) {
                    if (data.success) {
                        var row = document.getElementById('wishlist-row-' + wishlistId);
                        if (row) row.remove();
                        
                        var mobItem = document.getElementById('mobile-wishlist-item-' + wishlistId);
                        if (mobItem) mobItem.remove();

                        var remaining = document.querySelectorAll('.desktop-wishlist-table tbody tr').length;
                        if (remaining === 0) {
                            window.location.reload();
                        } else {
                            var countEl = document.getElementById('wishlist-count-summary');
                            if (countEl) countEl.textContent = remaining;
                        }
                    } else {
                        alert(data.message || 'Có lỗi xảy ra.');
                    }
                })
                .catch(function(err) {
                    console.error(err);
                    alert(err.message || 'Không thể kết nối đến máy chủ.');
                });
            });
        });
    });
</script>
@endpush
