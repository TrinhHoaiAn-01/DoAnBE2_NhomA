@extends('layouts.app', ['title' => 'Sản phẩm đã xem – NeoMart'])

@push('styles')
<style>
/* ===== RECENTLY VIEWED STYLES ===== */
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
    padding: 1.5rem 1rem;
    vertical-align: middle;
    border-bottom: 1px solid var(--border);
}
.cart-table tr:last-child td {
    border-bottom: none;
}
.cart-product-img {
    width: 68px;
    height: 68px;
    object-fit: contain;
    background: #f8fafc;
    border: 1px solid var(--border);
    border-radius: var(--radius-md, 10px);
    padding: 4px;
}
.cart-product-name {
    font-weight: 700;
    font-size: 0.95rem;
    color: var(--text-primary);
    text-decoration: none;
    line-height: 1.4;
    display: block;
    margin-bottom: 0.2rem;
    transition: color 0.2s;
}
.cart-product-name:hover {
    color: var(--primary);
}
.cart-product-cat {
    font-size: 0.75rem;
    color: var(--text-muted);
    font-weight: 600;
    text-transform: uppercase;
}
.cart-unit-price {
    font-weight: 600;
    color: var(--text-primary);
    font-size: 0.95rem;
}

/* Mobile card list (below md screen) */
.mobile-cart-list {
    display: none;
}
.mobile-cart-item {
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
.mobile-cart-img {
    width: 76px;
    height: 76px;
    object-fit: contain;
    background: #f8fafc;
    border: 1px solid var(--border);
    border-radius: var(--radius-md, 10px);
    flex-shrink: 0;
    padding: 4px;
}
.mobile-cart-details {
    flex: 1;
    display: flex;
    flex-direction: column;
}
.mobile-cart-name {
    font-weight: 700;
    font-size: 0.9rem;
    color: var(--text-primary);
    text-decoration: none;
    line-height: 1.35;
    margin-bottom: 0.25rem;
    padding-right: 1.5rem;
}
.mobile-cart-cat {
    font-size: 0.72rem;
    color: var(--text-muted);
    font-weight: 600;
    text-transform: uppercase;
    margin-bottom: 0.4rem;
}
.mobile-cart-price {
    font-weight: 700;
    color: var(--danger);
    font-size: 1rem;
    margin-bottom: 0.5rem;
}
.mobile-cart-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 0.25rem;
}
.mobile-cart-delete {
    position: absolute;
    top: 12px;
    right: 12px;
    color: var(--text-muted);
    border: none;
    background: transparent;
    cursor: pointer;
    transition: var(--transition);
}
.mobile-cart-delete:hover {
    color: var(--danger);
}

@media (max-width: 767.98px) {
    .desktop-cart-table {
        display: none;
    }
    .mobile-cart-list {
        display: block;
    }
}
</style>
@endpush

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4 mt-2">
        <div>
            <p class="text-uppercase text-primary small fw-extrabold mb-1" style="letter-spacing: 1px;">LỊCH SỬ XEM</p>
            <h1 class="cart-header-title mb-0">Sản phẩm đã xem</h1>
            <p class="text-secondary small mb-0">Danh sách các sản phẩm bạn đã xem gần đây.</p>
        </div>
        <div class="d-flex gap-2">
            @if($products->count() > 0)
                <button type="button" class="btn btn-outline-danger btn-continue-shopping px-4" onclick="clearAllHistory()">
                    <i class="bi bi-trash3 me-2"></i> Xóa tất cả lịch sử
                </button>
            @endif
            <a class="btn btn-outline-success btn-continue-shopping px-4" href="{{ route('products.index') }}">
                <i class="bi bi-arrow-left me-2"></i> Tiếp tục mua hàng
            </a>
        </div>
    </div>

    @if($products->count() > 0)
        <div class="row g-4">
            <div class="col-lg-12">
                {{-- Desktop Table View --}}
                <div class="cart-card desktop-cart-table">
                    <table class="table cart-table align-middle">
                        <thead>
                            <tr>
                                <th>SẢN PHẨM</th>
                                <th>ĐƠN GIÁ</th>
                                <th>TRẠNG THÁI</th>
                                <th style="width: 250px; text-align: right;">THAO TÁC</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr id="history-item-{{ $product->id }}">
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <img class="cart-product-img" src="{{ $product->image_url ?: 'https://placehold.co/100?text='.urlencode($product->name) }}" alt="{{ $product->name }}">
                                            <div>
                                                <a href="{{ route('products.show', $product) }}" class="cart-product-name">{{ $product->name }}</a>
                                                <span class="cart-product-cat">{{ $product->category?->name }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="cart-unit-price text-danger fw-bold">{{ number_format((float) $product->price, 0, ',', '.') }}đ</span>
                                    </td>
                                    <td>
                                        @if($product->stock > 0)
                                            <span class="badge bg-success-subtle text-success px-2 py-1" style="font-size: 0.8rem; border-radius: 4px;">Còn hàng ({{ $product->stock }})</span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger px-2 py-1" style="font-size: 0.8rem; border-radius: 4px;">Hết hàng</span>
                                        @endif
                                    </td>
                                    <td style="text-align: right;">
                                        <div class="d-flex gap-2 justify-content-end align-items-center">
                                            @if($product->stock > 0)
                                            <form action="{{ route('cart.add', $product) }}" method="POST" class="m-0">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm px-3 fw-bold" style="border-radius: 8px;">
                                                    <i class="bi bi-cart-plus me-1"></i> Thêm vào giỏ
                                                </button>
                                            </form>
                                            @endif
                                            <button class="btn btn-outline-danger btn-sm px-2" onclick="removeHistoryItem('{{ $product->id }}')" title="Xóa khỏi lịch sử">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Mobile Card List View --}}
                <div class="mobile-cart-list">
                    @foreach ($products as $product)
                        <div class="mobile-cart-item d-flex align-items-center" id="mob-history-item-{{ $product->id }}">
                            <img class="mobile-cart-img" src="{{ $product->image_url ?: 'https://placehold.co/100?text='.urlencode($product->name) }}" alt="{{ $product->name }}">
                            <div class="mobile-cart-details">
                                <a href="{{ route('products.show', $product) }}" class="mobile-cart-name">{{ $product->name }}</a>
                                <span class="mobile-cart-cat">{{ $product->category?->name }}</span>
                                <span class="mobile-cart-price">{{ number_format($product->price, 0, ',', '.') }}đ</span>
                                
                                <div class="mobile-cart-footer mt-2">
                                    @if($product->stock > 0)
                                    <form action="{{ route('cart.add', $product) }}" method="POST" class="m-0">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm px-2 py-1 fw-bold" style="border-radius: 6px; font-size: 0.8rem;">
                                            <i class="bi bi-cart-plus"></i> Thêm vào giỏ
                                        </button>
                                    </form>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger px-2 py-1" style="font-size: 0.75rem; border-radius: 4px;">Hết hàng</span>
                                    @endif
                                </div>
                            </div>
                            
                            {{-- Delete action --}}
                            <button type="button" class="mobile-cart-delete" onclick="removeHistoryItem('{{ $product->id }}')" title="Xóa">
                                <i class="bi bi-trash fs-5"></i>
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @else
        <div class="cart-card p-5 text-center">
            <div class="py-4">
                <div class="mb-3 text-muted">
                    <i class="bi bi-clock-history" style="font-size: 4rem;"></i>
                </div>
                <h5 class="fw-bold mb-2">Lịch sử xem trống</h5>
                <p class="text-muted small mb-4">Bạn chưa xem sản phẩm nào gần đây.</p>
                <a class="btn btn-success px-4 fw-bold" href="{{ route('products.index') }}" style="border-radius: 10px;">
                    Khám phá sản phẩm
                </a>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
<script>
    function removeHistoryItem(productId) {
        if (!confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi lịch sử đã xem?')) {
            return;
        }

        const url = `/recently-viewed/${productId}`;

        fetch(url, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const element = document.getElementById(`history-item-${productId}`);
                if (element) {
                    element.remove();
                }
                const mobElement = document.getElementById(`mob-history-item-${productId}`);
                if (mobElement) {
                    mobElement.remove();
                }

                // Check if empty
                if (document.querySelectorAll('.mobile-cart-item').length === 0 && document.querySelectorAll('tbody tr').length === 0) {
                    window.location.reload();
                }
            } else {
                alert(data.message || 'Có lỗi xảy ra.');
            }
        })
        .catch(err => {
            console.error(err);
            alert('Không thể kết nối đến máy chủ.');
        });
    }

    function clearAllHistory() {
        if (!confirm('Bạn có chắc muốn xóa toàn bộ lịch sử sản phẩm đã xem?')) {
            return;
        }

        const url = '/recently-viewed/clear';

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                alert(data.message || 'Có lỗi xảy ra.');
            }
        })
        .catch(err => {
            console.error(err);
            alert('Không thể kết nối đến máy chủ.');
        });
    }
</script>
@endpush
