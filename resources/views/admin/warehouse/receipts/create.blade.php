@extends('layouts.admin', ['title' => 'Tạo Phiếu Nhập Kho'])

@section('content')
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('admin.warehouse.receipts') }}" class="btn btn-outline-secondary me-3"><i class="bi bi-arrow-left"></i> Trở về</a>
        <div>
            <h1 class="h3 fw-bold mb-0">Tạo Phiếu Nhập Kho</h1>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            Vui lòng kiểm tra lại thông tin nhập.
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.warehouse.receipts.store') }}" method="POST">
        @csrf
        <div class="row g-4">
            <div class="col-xl-4">
                <div class="surface p-4 h-100">
                    <h5 class="fw-bold mb-4">Thông tin chung</h5>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nhà cung cấp <span class="text-danger">*</span></label>
                        <select name="supplier_id" class="form-select" required>
                            <option value="">-- Chọn Nhà cung cấp --</option>
                            @foreach($suppliers as $sup)
                                <option value="{{ $sup->id }}">{{ $sup->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Ghi chú</label>
                        <textarea name="note" class="form-control" rows="4" placeholder="Nhập ghi chú (nếu có)..."></textarea>
                    </div>

                    <hr>
                    <div class="d-flex justify-content-between align-items-center fs-5 fw-bold text-primary">
                        <span>Tổng tiền:</span>
                        <span id="grandTotalDisplay">0đ</span>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                            <i class="bi bi-check-circle me-1"></i> Hoàn tất nhập kho
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-xl-8">
                <div class="surface p-4 h-100">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0">Chi tiết sản phẩm nhập</h5>
                        <button type="button" class="btn btn-sm btn-outline-success" id="btn-add-item">
                            <i class="bi bi-plus-circle"></i> Thêm Dòng
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table" id="itemsTable">
                            <thead class="table-light">
                                <tr>
                                    <th width="30%">Sản phẩm</th>
                                    <th width="15%">Mã lô (Tùy chọn)</th>
                                    <th width="15%">HSD (Tùy chọn)</th>
                                    <th width="15%">Số lượng</th>
                                    <th width="15%">Giá nhập (đ)</th>
                                    <th width="5%">Thành tiền</th>
                                    <th width="5%"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Dòng nhập mặc định -->
                                <tr class="item-row">
                                    <td>
                                        <select name="products[0][id]" class="form-select product-select" required>
                                            <option value="">Chọn SP...</option>
                                            @foreach($products as $p)
                                                <option value="{{ $p->id }}" data-price="{{ $p->price }}">{{ $p->name }} (Kho: {{ $p->stock }})</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="products[0][batch_code]" class="form-control" placeholder="Mã lô...">
                                    </td>
                                    <td>
                                        <input type="date" name="products[0][expires_at]" class="form-control">
                                    </td>
                                    <td>
                                        <input type="number" name="products[0][quantity]" class="form-control qty-input" value="1" min="1" required>
                                    </td>
                                    <td>
                                        <input type="number" name="products[0][price]" class="form-control price-input" value="0" min="0" step="1000" required>
                                    </td>
                                    <td class="align-middle fw-medium subtotal-col text-end">0đ</td>
                                    <td class="align-middle text-end">
                                        <button type="button" class="btn btn-sm btn-link text-danger btn-remove"><i class="bi bi-trash"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let rowCount = 1;

        const tableBody = document.querySelector('#itemsTable tbody');
        const btnAdd = document.getElementById('btn-add-item');
        const grandTotalDisplay = document.getElementById('grandTotalDisplay');

        // Render product options
        const productOptions = `
            <option value="">Chọn SP...</option>
            @foreach($products as $p)
                <option value="{{ $p->id }}" data-price="{{ $p->price }}">{{ $p->name }}</option>
            @endforeach
        `;

        function calculateTotals() {
            let grandTotal = 0;
            document.querySelectorAll('.item-row').forEach(row => {
                const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
                const price = parseFloat(row.querySelector('.price-input').value) || 0;
                const subtotal = qty * price;
                
                row.querySelector('.subtotal-col').textContent = new Intl.NumberFormat('vi-VN').format(subtotal) + 'đ';
                grandTotal += subtotal;
            });
            grandTotalDisplay.textContent = new Intl.NumberFormat('vi-VN').format(grandTotal) + 'đ';
        }

        btnAdd.addEventListener('click', () => {
            const tr = document.createElement('tr');
            tr.className = 'item-row';
            tr.innerHTML = `
                <td>
                    <select name="products[${rowCount}][id]" class="form-select product-select" required>
                        ${productOptions}
                    </select>
                </td>
                <td><input type="text" name="products[${rowCount}][batch_code]" class="form-control" placeholder="Mã lô..."></td>
                <td><input type="date" name="products[${rowCount}][expires_at]" class="form-control"></td>
                <td><input type="number" name="products[${rowCount}][quantity]" class="form-control qty-input" value="1" min="1" required></td>
                <td><input type="number" name="products[${rowCount}][price]" class="form-control price-input" value="0" min="0" step="1000" required></td>
                <td class="align-middle fw-medium subtotal-col text-end">0đ</td>
                <td class="align-middle text-end"><button type="button" class="btn btn-sm btn-link text-danger btn-remove"><i class="bi bi-trash"></i></button></td>
            `;
            tableBody.appendChild(tr);
            rowCount++;
        });

        tableBody.addEventListener('input', function(e) {
            if (e.target.classList.contains('qty-input') || e.target.classList.contains('price-input')) {
                calculateTotals();
            }
        });

        tableBody.addEventListener('change', function(e) {
            if (e.target.classList.contains('product-select')) {
                // Tự động gán giá vốn bằng giá bán (demo đơn giản)
                const selectedOption = e.target.options[e.target.selectedIndex];
                const priceStr = selectedOption.getAttribute('data-price');
                if (priceStr) {
                    const row = e.target.closest('tr');
                    // Gợi ý giá nhập = 70% giá bán chẳng hạn, hoặc giữ nguyên
                    const suggestedPrice = Math.round(parseFloat(priceStr) * 0.7);
                    row.querySelector('.price-input').value = suggestedPrice;
                    calculateTotals();
                }
            }
        });

        tableBody.addEventListener('click', function(e) {
            if (e.target.closest('.btn-remove')) {
                if (document.querySelectorAll('.item-row').length > 1) {
                    e.target.closest('tr').remove();
                    calculateTotals();
                } else {
                    alert('Phải có ít nhất 1 sản phẩm để nhập kho!');
                }
            }
        });
    });
</script>
@endpush
