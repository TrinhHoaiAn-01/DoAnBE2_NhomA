@extends('layouts.app', ['title' => 'Giỏ hàng – NeoMart'])

@push('styles')
<style>
/* ===== CART PAGE STYLES ===== */
/* Custom checkbox styles */
.form-check-input:checked {
    background-color: var(--primary) !important;
    border-color: var(--primary) !important;
}
.form-check-input {
    border-radius: 4px;
    border: 2px solid var(--border);
    cursor: pointer;
    transition: all 0.2s ease;
}
.form-check-input:focus {
    box-shadow: 0 0 0 3px rgba(0, 136, 72, 0.15);
    border-color: var(--primary);
}
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
.cart-subtotal-price {
    font-weight: 700;
    color: var(--danger);
    font-size: 1.05rem;
}

/* Quantity controls in table */
.cart-qty-control {
    display: flex;
    align-items: center;
    border: 1.5px solid var(--border);
    border-radius: 8px;
    overflow: hidden;
    width: fit-content;
    background: #fff;
}
.cart-qty-btn {
    width: 32px;
    height: 32px;
    background: var(--surface-2);
    border: none;
    color: var(--text-primary);
    font-size: 1rem;
    font-weight: bold;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: var(--transition);
}
.cart-qty-btn:hover {
    background: var(--primary-light);
    color: var(--primary);
}
.cart-qty-input {
    width: 40px;
    height: 32px;
    border: none;
    border-left: 1px solid var(--border);
    border-right: 1px solid var(--border);
    text-align: center;
    font-weight: 700;
    font-size: 0.9rem;
    color: var(--text-primary);
    outline: none;
    background: #fff;
}
.cart-qty-input::-webkit-outer-spin-button,
.cart-qty-input::-webkit-inner-spin-button {
    -webkit-appearance: none;
}

/* Sidebar Order Info */
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
.summary-total-value {
    font-weight: 950;
    font-size: 1.45rem;
    color: var(--danger);
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
            <p class="text-uppercase text-primary small fw-extrabold mb-1" style="letter-spacing: 1px;">MUA HÀNG</p>
            <h1 class="cart-header-title mb-0">Giỏ hàng của bạn</h1>
            <p class="text-secondary small mb-0">Kiểm tra sản phẩm và số lượng trước khi đặt hàng.</p>
        </div>
        <a class="btn btn-outline-success btn-continue-shopping px-4" href="{{ route('products.index') }}">
            <i class="bi bi-arrow-left me-2"></i> Tiếp tục mua hàng
        </a>
    </div>

    @if (count($items) > 0)
        <div class="row g-4">
            {{-- Left column - Product list --}}
            <div class="col-lg-8">
                {{-- Desktop Table View --}}
                <div class="cart-card desktop-cart-table">
                    <table class="table cart-table align-middle">
                        <thead>
                            <tr>
                                <th style="width: 50px; text-align: center;">
                                    <input type="checkbox" id="select-all" class="form-check-input select-all-checkbox" checked>
                                </th>
                                <th>SẢN PHẨM</th>
                                <th>ĐƠN GIÁ</th>
                                <th>SỐ LƯỢNG</th>
                                <th>TẠM TÍNH</th>
                                <th>THAO TÁC</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $item)
                                <tr>
                                    <td style="text-align: center; width: 50px;">
                                        <input type="checkbox" class="form-check-input item-checkbox" value="{{ $item['product']->id }}" data-price="{{ $item['product']->price }}" data-quantity="{{ $item['quantity'] }}" checked>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <img class="cart-product-img" src="{{ $item['product']->image_url ?: 'https://placehold.co/100?text='.urlencode($item['product']->name) }}" alt="{{ $item['product']->name }}">
                                            <div>
                                                <a href="{{ route('products.show', $item['product']) }}" class="cart-product-name">{{ $item['product']->name }}</a>
                                                <span class="cart-product-cat">{{ $item['product']->category?->name }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="cart-unit-price">{{ number_format((float) $item['product']->price, 0, ',', '.') }}đ</span>
                                    </td>
                                    <td>
                                        <form id="update-form-{{ $item['product']->id }}" method="post" action="{{ route('cart.update', $item['product']) }}">
                                            @csrf
                                            @method('patch')
                                            <div class="cart-qty-control">
                                                <button type="button" class="cart-qty-btn" onclick="updateQty('{{ $item['product']->id }}', -1)">−</button>
                                                <input type="number" name="quantity" id="qty-{{ $item['product']->id }}" value="{{ $item['quantity'] }}" min="1" max="{{ max($item['product']->stock, 1) }}" readonly class="cart-qty-input">
                                                <button type="button" class="cart-qty-btn" onclick="updateQty('{{ $item['product']->id }}', 1)">+</button>
                                            </div>
                                        </form>
                                    </td>
                                    <td>
                                        <span class="cart-subtotal-price">{{ number_format($item['subtotal'], 0, ',', '.') }}đ</span>
                                    </td>
                                    <td>
                                        <form method="post" action="{{ route('cart.remove', $item['product']) }}">
                                            @csrf
                                            @method('delete')
                                            <button class="btn btn-link text-muted p-1" type="submit" title="Xóa khỏi giỏ hàng">
                                                <i class="bi bi-trash fs-5"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Mobile Card List View --}}
                <div class="mobile-cart-list">
                    <div class="mb-3 p-3 bg-light rounded-3 d-flex align-items-center justify-content-between border">
                        <div class="form-check m-0 d-flex align-items-center gap-2">
                            <input class="form-check-input select-all-checkbox-mobile" type="checkbox" id="select-all-mobile" checked>
                            <label class="form-check-label fw-bold small mb-0 cursor-pointer" for="select-all-mobile">Chọn tất cả</label>
                        </div>
                    </div>
                    @foreach ($items as $item)
                        <div class="mobile-cart-item d-flex align-items-center">
                            <div class="me-2">
                                <input type="checkbox" class="form-check-input item-checkbox-mobile" value="{{ $item['product']->id }}" data-price="{{ $item['product']->price }}" data-quantity="{{ $item['quantity'] }}" checked>
                            </div>
                            <img class="mobile-cart-img" src="{{ $item['product']->image_url ?: 'https://placehold.co/100?text='.urlencode($item['product']->name) }}" alt="{{ $item['product']->name }}">
                            <div class="mobile-cart-details">
                                <a href="{{ route('products.show', $item['product']) }}" class="mobile-cart-name">{{ $item['product']->name }}</a>
                                <span class="mobile-cart-cat">{{ $item['product']->category?->name }}</span>
                                
                                <span class="mobile-cart-price">{{ number_format($item['subtotal'], 0, ',', '.') }}đ</span>
                                
                                <div class="mobile-cart-footer">
                                    <form id="mob-update-form-{{ $item['product']->id }}" method="post" action="{{ route('cart.update', $item['product']) }}">
                                        @csrf
                                        @method('patch')
                                        <div class="cart-qty-control">
                                            <button type="button" class="cart-qty-btn" onclick="updateQtyMobile('{{ $item['product']->id }}', -1)">−</button>
                                            <input type="number" name="quantity" id="mob-qty-{{ $item['product']->id }}" value="{{ $item['quantity'] }}" min="1" max="{{ max($item['product']->stock, 1) }}" readonly class="cart-qty-input">
                                            <button type="button" class="cart-qty-btn" onclick="updateQtyMobile('{{ $item['product']->id }}', 1)">+</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            
                            {{-- Delete action --}}
                            <form method="post" action="{{ route('cart.remove', $item['product']) }}" class="m-0">
                                @csrf
                                @method('delete')
                                <button type="submit" class="mobile-cart-delete" title="Xóa">
                                    <i class="bi bi-trash fs-5"></i>
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Right column - Summary card --}}
            <div class="col-lg-4">
                <div class="summary-card">
                    <h2 class="summary-title">Thông tin đơn hàng</h2>
                    
                    <div class="summary-row">
                        <span class="summary-label">Tạm tính</span>
                        <span class="summary-value">{{ number_format($total, 0, ',', '.') }}đ</span>
                    </div>
                    
                    <div class="summary-row total-row">
                        <span class="summary-total-label">Tổng cộng</span>
                        <div class="text-end">
                            <span class="summary-total-value">{{ number_format($total, 0, ',', '.') }}đ</span>
                            <div class="text-muted small mt-1 fw-medium" style="font-size: 0.72rem;">(Đã bao gồm VAT nếu có)</div>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <a class="btn-checkout-confirm" id="btn-checkout-confirm" href="{{ route('checkout.index') }}">
                            Xác nhận đặt hàng <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="cart-card p-5 text-center">
            <div class="py-4">
                <div class="mb-3 text-muted">
                    <i class="bi bi-bag-x" style="font-size: 4rem;"></i>
                </div>
                <h5 class="fw-bold mb-2">Giỏ hàng của bạn đang trống</h5>
                <p class="text-muted small mb-4">Vui lòng chọn sản phẩm từ cửa hàng để bắt đầu mua sắm.</p>
                <a class="btn btn-success px-4 fw-bold" href="{{ route('products.index') }}" style="border-radius: 10px;">
                    Khám phá sản phẩm
                </a>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
<script>
    // Update quantity for desktop table
    function updateQty(productId, change) {
        const input = document.getElementById('qty-' + productId);
        const form = document.getElementById('update-form-' + productId);
        const min = parseInt(input.min) || 1;
        const max = parseInt(input.max) || 99;
        let val = parseInt(input.value) + change;
        if (val >= min && val <= max) {
            input.value = val;
            form.submit();
        }
    }

    // Update quantity for mobile cards
    function updateQtyMobile(productId, change) {
        const input = document.getElementById('mob-qty-' + productId);
        const form = document.getElementById('mob-update-form-' + productId);
        const min = parseInt(input.min) || 1;
        const max = parseInt(input.max) || 99;
        let val = parseInt(input.value) + change;
        if (val >= min && val <= max) {
            input.value = val;
            form.submit();
        }
    }

    // Checkbox synchronization and totals calculation logic
    document.addEventListener('DOMContentLoaded', function() {
        // Check if we have saved selected ids in localStorage
        let selectedIds = null;
        try {
            const stored = localStorage.getItem('selected_cart_ids');
            if (stored) {
                selectedIds = JSON.parse(stored);
            }
        } catch (e) {
            console.error("Error parsing selected_cart_ids from localStorage", e);
        }

        // Initialize checkbox states
        if (selectedIds !== null) {
            document.querySelectorAll('.item-checkbox, .item-checkbox-mobile').forEach(cb => {
                cb.checked = selectedIds.includes(parseInt(cb.value)) || selectedIds.includes(cb.value.toString());
            });
        } else {
            saveCheckedStates();
        }

        updateTotalsAndCheckoutUrl();
        updateSelectAllState();

        // Event listener for checkboxes
        document.querySelectorAll('.item-checkbox, .item-checkbox-mobile').forEach(cb => {
            cb.addEventListener('change', function() {
                // Sync the other checkbox (mobile/desktop) for the same product
                const val = this.value;
                const isChecked = this.checked;
                document.querySelectorAll(`.item-checkbox[value="${val}"], .item-checkbox-mobile[value="${val}"]`).forEach(other => {
                    other.checked = isChecked;
                });
                
                saveCheckedStates();
                updateTotalsAndCheckoutUrl();
                updateSelectAllState();
            });
        });

        // Event listener for select all
        document.getElementById('select-all')?.addEventListener('change', function() {
            const isChecked = this.checked;
            document.querySelectorAll('.item-checkbox, .item-checkbox-mobile, .select-all-checkbox-mobile').forEach(other => {
                other.checked = isChecked;
            });
            saveCheckedStates();
            updateTotalsAndCheckoutUrl();
        });

        document.getElementById('select-all-mobile')?.addEventListener('change', function() {
            const isChecked = this.checked;
            document.querySelectorAll('.item-checkbox, .item-checkbox-mobile, .select-all-checkbox').forEach(other => {
                other.checked = isChecked;
            });
            saveCheckedStates();
            updateTotalsAndCheckoutUrl();
        });

        function saveCheckedStates() {
            const ids = [];
            document.querySelectorAll('.item-checkbox:checked').forEach(cb => {
                ids.push(parseInt(cb.value));
            });
            localStorage.setItem('selected_cart_ids', JSON.stringify(ids));
        }

        function updateSelectAllState() {
            const totalCheckboxes = document.querySelectorAll('.item-checkbox').length;
            const checkedCheckboxes = document.querySelectorAll('.item-checkbox:checked').length;
            const isAllChecked = (totalCheckboxes === checkedCheckboxes) && totalCheckboxes > 0;
            
            const selectAll = document.getElementById('select-all');
            const selectAllMobile = document.getElementById('select-all-mobile');
            if (selectAll) selectAll.checked = isAllChecked;
            if (selectAllMobile) selectAllMobile.checked = isAllChecked;
        }

        function updateTotalsAndCheckoutUrl() {
            let total = 0;
            const checkedCheckboxes = document.querySelectorAll('.item-checkbox:checked');
            checkedCheckboxes.forEach(cb => {
                const price = parseFloat(cb.dataset.price);
                const quantity = parseInt(cb.dataset.quantity);
                total += price * quantity;
            });

            // Format currency helper
            const formatted = new Intl.NumberFormat('vi-VN').format(total) + 'đ';
            
            // Update displays
            document.querySelectorAll('.summary-value, .summary-total-value').forEach(el => {
                el.textContent = formatted;
            });

            // Update checkout link & button state
            const checkoutBtn = document.getElementById('btn-checkout-confirm');
            if (checkoutBtn) {
                if (checkedCheckboxes.length === 0) {
                    checkoutBtn.classList.add('disabled');
                    checkoutBtn.style.pointerEvents = 'none';
                    checkoutBtn.style.opacity = '0.5';
                    checkoutBtn.href = 'javascript:void(0)';
                } else {
                    const ids = [];
                    checkedCheckboxes.forEach(cb => {
                        ids.push(cb.value);
                    });
                    checkoutBtn.classList.remove('disabled');
                    checkoutBtn.style.pointerEvents = 'auto';
                    checkoutBtn.style.opacity = '1';
                    checkoutBtn.href = "{{ route('checkout.index') }}?selected_ids=" + ids.join(',');
                }
            }
        }
    });
</script>
@endpush
