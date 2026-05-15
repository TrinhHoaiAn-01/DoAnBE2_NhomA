@extends('layouts.admin', ['title' => 'Tạo phiếu xuất kho'])

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold mb-0">Tạo phiếu xuất kho</h1>
        <a href="{{ route('admin.warehouse.issues') }}" class="btn btn-outline-secondary">Hủy bỏ</a>
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

    <form action="{{ route('admin.warehouse.issues.store') }}" method="POST">
        @csrf
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="card-title mb-0 fw-bold">Danh sách sản phẩm xuất</h5>
                    </div>
                    <div class="card-body p-0">
                        <table class="table mb-0" id="productTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th width="150">Số lượng xuất</th>
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
                                                    {{ $p->name }} (Tồn: {{ $p->stock }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="products[0][quantity]" class="form-control qty-input" min="1" required>
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
                        <h5 class="fw-bold mb-3">Thông tin xuất</h5>
                        
                        <div class="mb-3">
                            <label class="form-label">Lý do xuất kho <span class="text-danger">*</span></label>
                            <select name="reason" class="form-select" required>
                                <option value="Bán lẻ">Bán lẻ (Ngoại hệ thống)</option>
                                <option value="Xuất bảo hành">Xuất bảo hành</option>
                                <option value="Hư hỏng / Hao hụt">Hư hỏng / Hao hụt</option>
                                <option value="Xuất nội bộ">Xuất nội bộ</option>
                                <option value="Khác">Khác...</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Ghi chú thêm</label>
                            <textarea name="note" class="form-control" rows="3" placeholder="Chi tiết lý do xuất..."></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 fw-bold py-2">Xác nhận xuất kho</button>
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
        newRow.querySelector('.qty-input').name = `products[${rowIdx}][quantity]`;
        newRow.querySelector('.qty-input').value = '';
        
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

    // Validate max stock
    document.querySelector('#productTable').addEventListener('change', function(e) {
        if (e.target.classList.contains('product-select') || e.target.classList.contains('qty-input')) {
            const row = e.target.closest('.product-row');
            const select = row.querySelector('.product-select');
            const input = row.querySelector('.qty-input');
            const selectedOption = select.options[select.selectedIndex];
            
            if(selectedOption && selectedOption.value !== "") {
                const maxStock = parseInt(selectedOption.getAttribute('data-stock'));
                input.max = maxStock;
                
                if (parseInt(input.value) > maxStock) {
                    alert('Số lượng xuất không được lớn hơn tồn kho!');
                    input.value = maxStock;
                }
            }
        }
    });
</script>
@endpush
