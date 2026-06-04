@extends('layouts.admin', ['title' => 'Phiếu Kiểm Kê'])

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold mb-0">Tạo phiếu kiểm kê</h1>
        <a href="{{ route('admin.warehouse.checks') }}" class="btn btn-outline-secondary">Hủy bỏ</a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.warehouse.checks.store') }}" method="POST">
        @csrf
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="card-title mb-0 fw-bold">Danh sách kiểm kê</h5>
                    </div>
                    <div class="card-body p-0">
                        <table class="table mb-0" id="productTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th width="150">Tồn hệ thống</th>
                                    <th width="150">Tồn thực tế</th>
                                    <th width="100">Chênh lệch</th>
                                    <th width="50"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="product-row">
                                    <td>
                                        <select name="products[0][id]" class="form-select product-select" required>
                                            <option value="">Chọn sản phẩm...</option>
                                            @foreach($products as $p)
                                                <option value="{{ $p->id }}" data-stock="{{ $p->stock }}">
                                                    {{ $p->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control sys-stock text-center" readonly value="0">
                                    </td>
                                    <td>
                                        <input type="number" name="products[0][actual_stock]" class="form-control actual-stock" min="0" required disabled>
                                    </td>
                                    <td>
                                        <span class="diff-span fw-bold">-</span>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-outline-danger btn-remove"><i class="bi bi-trash"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="p-3 border-top">
                            <button type="button" class="btn btn-outline-primary" id="btnAddRow"><i class="bi bi-plus-lg"></i> Thêm dòng</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">Thông tin kiểm kê</h5>
                        
                        <div class="mb-4">
                            <label class="form-label">Ghi chú kiểm kê</label>
                            <textarea name="note" class="form-control" rows="4" placeholder="Ví dụ: Kiểm kê định kỳ tháng 5..."></textarea>
                        </div>

                        <div class="alert alert-warning small">
                            <i class="bi bi-exclamation-triangle-fill"></i> 
                            Khi xác nhận, tồn kho hệ thống sẽ bị ghi đè bằng Tồn kho thực tế. Lịch sử kho sẽ tự động điều chỉnh (+) hoặc (-) để cân bằng.
                        </div>

                        <button type="submit" class="btn btn-primary w-100 fw-bold py-2">Xác nhận Kiểm kê</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
<script>
    let rowIdx = 1;
    document.getElementById('btnAddRow').addEventListener('click', function() {
        const tbody = document.querySelector('#productTable tbody');
        const firstRow = tbody.querySelector('.product-row');
        const newRow = firstRow.cloneNode(true);
        
        newRow.querySelector('select').name = `products[${rowIdx}][id]`;
        newRow.querySelector('select').value = '';
        newRow.querySelector('.sys-stock').value = '0';
        newRow.querySelector('.actual-stock').name = `products[${rowIdx}][actual_stock]`;
        newRow.querySelector('.actual-stock').value = '';
        newRow.querySelector('.actual-stock').disabled = true;
        newRow.querySelector('.diff-span').innerHTML = '-';
        newRow.querySelector('.diff-span').className = 'diff-span fw-bold';
        
        tbody.appendChild(newRow);
        rowIdx++;
    });

    document.querySelector('#productTable').addEventListener('click', function(e) {
        if (e.target.closest('.btn-remove')) {
            const rows = document.querySelectorAll('.product-row');
            if (rows.length > 1) {
                e.target.closest('.product-row').remove();
            } else {
                alert('Phải có ít nhất 1 sản phẩm!');
            }
        }
    });

    // Update diff on change
    document.querySelector('#productTable').addEventListener('change', function(e) {
        if (e.target.classList.contains('product-select')) {
            const row = e.target.closest('.product-row');
            const select = e.target;
            const actualInput = row.querySelector('.actual-stock');
            const sysInput = row.querySelector('.sys-stock');
            
            const selectedOption = select.options[select.selectedIndex];
            
            if(selectedOption && selectedOption.value !== "") {
                const stock = parseInt(selectedOption.getAttribute('data-stock'));
                sysInput.value = stock;
                actualInput.disabled = false;
                actualInput.focus();
                calculateDiff(row);
            } else {
                sysInput.value = '0';
                actualInput.disabled = true;
                actualInput.value = '';
                row.querySelector('.diff-span').innerHTML = '-';
            }
        }
    });

    document.querySelector('#productTable').addEventListener('input', function(e) {
        if (e.target.classList.contains('actual-stock')) {
            calculateDiff(e.target.closest('.product-row'));
        }
    });

    function calculateDiff(row) {
        const sysStock = parseInt(row.querySelector('.sys-stock').value) || 0;
        const actualStock = parseInt(row.querySelector('.actual-stock').value);
        const diffSpan = row.querySelector('.diff-span');

        if(isNaN(actualStock)) {
            diffSpan.innerHTML = '-';
            diffSpan.className = 'diff-span fw-bold';
            return;
        }

        const diff = actualStock - sysStock;
        if(diff > 0) {
            diffSpan.innerHTML = '+' + diff;
            diffSpan.className = 'diff-span fw-bold text-success';
        } else if(diff < 0) {
            diffSpan.innerHTML = diff;
            diffSpan.className = 'diff-span fw-bold text-danger';
        } else {
            diffSpan.innerHTML = '0';
            diffSpan.className = 'diff-span fw-bold text-muted';
        }
    }
</script>
@endpush
