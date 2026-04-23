@extends('layouts.admin')

@section('title', 'Quản lý Nhà cung cấp')

@section('content')
<div class="card border-0 shadow-sm rounded-3">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold">Danh sách đối tác cung ứng</h5>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addSupplierModal">
            <i class="bi bi-plus-lg me-1"></i> Thêm mới
        </button>
    </div>
    
    @if(session('success'))
        <div class="alert alert-success m-3 mb-0 border-0 border-start border-4 border-success bg-success bg-opacity-10">
            {{ session('success') }}
        </div>
    @endif

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Tên Nhà cung cấp</th>
                        <th>Người liên hệ</th>
                        <th>Số điện thoại</th>
                        <th>Địa chỉ</th>
                        <th class="text-center">Trạng thái</th>
                        <th class="text-end pe-4">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suppliers as $supplier)
                    <tr>
                        <td class="ps-4 fw-bold text-primary">{{ $supplier->name }}</td>
                        <td>{{ $supplier->contact_person ?? '---' }}</td>
                        <td>{{ $supplier->phone ?? '---' }}</td>
                        <td class="text-muted small">{{ $supplier->address ?? '---' }}</td>
                        <td class="text-center">
                            <span class="badge {{ $supplier->is_active ? 'bg-success' : 'bg-secondary' }} rounded-pill">
                                {{ $supplier->is_active ? 'Đang hợp tác' : 'Ngừng' }}
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            <form action="{{ route('admin.suppliers.destroy', $supplier->id) }}" method="POST" onsubmit="return confirm('Xóa nhà cung cấp này?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-link text-danger p-0">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">Chưa có dữ liệu nhà cung cấp.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Thêm mới -->
<div class="modal fade" id="addSupplierModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.suppliers.store') }}" method="POST" class="modal-content border-0 shadow">
            @csrf
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold">Thêm Nhà cung cấp mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Tên nhà cung cấp <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" placeholder="VD: Công ty TNHH ABC" required>
                </div>
                <div class="row mb-3">
                    <div class="col">
                        <label class="form-label fw-bold">Người liên hệ</label>
                        <input type="text" name="contact_person" class="form-control" placeholder="Tên quản lý">
                    </div>
                    <div class="col">
                        <label class="form-label fw-bold">Số điện thoại</label>
                        <input type="text" name="phone" class="form-control" placeholder="09xx...">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Địa chỉ</label>
                    <textarea name="address" class="form-control" rows="2" placeholder="Địa chỉ trụ sở"></textarea>
                </div>
            </div>
            <div class="modal-footer border-top-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy</button>
                <button type="submit" class="btn btn-primary px-4">Lưu thông tin</button>
            </div>
        </form>
    </div>
</div>
@endsection
