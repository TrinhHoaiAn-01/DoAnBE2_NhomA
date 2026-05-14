@extends('layouts.admin')

@section('title', 'Quản lý Nhà Cung Cấp')

@section('content')
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h4 class="mb-0 fw-bold text-dark">Danh sách Nhà Cung Cấp</h4>
            <p class="text-muted mb-0 small">Quản lý thông tin và dữ liệu các nhà cung cấp của hệ thống.</p>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <button class="btn btn-primary px-4 fw-medium shadow-sm" data-bs-toggle="modal" data-bs-target="#addSupplierModal">
                <i class="bi bi-plus-circle me-1"></i> Thêm Nhà Cung Cấp Mới
            </button>
        </div>
    </div>

    {{-- Hiển thị thông báo thành công hoặc lỗi --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> Vui lòng kiểm tra lại thông tin nhập vào.
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Bảng dữ liệu nhà cung cấp --}}
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4" style="width: 50px;">ID</th>
                            <th>Tên Nhà Cung Cấp</th>
                            <th>Số Điện Thoại</th>
                            <th>Ngày Tạo</th>
                            <th class="text-end pe-4" style="width: 150px;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($suppliers as $supplier)
                            <tr>
                                <td class="ps-4 fw-medium text-muted">#{{ $supplier->id }}</td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $supplier->name }}</div>
                                </td>
                                <td>
                                    @if($supplier->phone)
                                        <span class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill fw-medium">
                                            <i class="bi bi-telephone-fill me-1"></i> {{ $supplier->phone }}
                                        </span>
                                    @else
                                        <span class="text-muted fst-italic">Chưa cập nhật</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="text-muted small">
                                        <i class="bi bi-calendar3 me-1"></i> {{ $supplier->created_at->format('d/m/Y H:i') }}
                                    </div>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-2 align-items-center">
                                        <button type="button" class="btn btn-sm btn-outline-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#editSupplierModal{{ $supplier->id }}" title="Sửa">
                                            <i class="bi bi-pencil-square"></i> Sửa
                                        </button>
                                        <form action="{{ route('admin.suppliers.destroy', $supplier->id) }}" method="POST" class="m-0" onsubmit="return confirm('Bạn có chắc chắn muốn xóa nhà cung cấp này không? Mọi dữ liệu liên quan có thể bị ảnh hưởng.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger shadow-sm" title="Xóa">
                                                <i class="bi bi-trash"></i> Xóa
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="text-muted mb-3">
                                        <i class="bi bi-inboxes fs-1"></i>
                                    </div>
                                    <h6 class="fw-bold mb-1">Chưa có nhà cung cấp nào</h6>
                                    <p class="small mb-0">Hãy bấm nút "Thêm Nhà Cung Cấp Mới" để bắt đầu.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal Thêm Nhà Cung Cấp --}}
    <div class="modal fade" id="addSupplierModal" tabindex="-1" aria-labelledby="addSupplierModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold" id="addSupplierModalLabel">Thêm Nhà Cung Cấp Mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-4">
                    <p class="text-muted small mb-4">Vui lòng điền đầy đủ thông tin nhà cung cấp bên dưới.</p>
                    <form action="{{ route('admin.suppliers.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="name" class="form-label fw-medium text-dark">Tên Nhà Cung Cấp <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-lg bg-light" id="name" name="name" placeholder="VD: Công ty TNHH Cung Cấp A" required>
                        </div>
                        <div class="mb-4">
                            <label for="phone" class="form-label fw-medium text-dark">Số Điện Thoại</label>
                            <input type="text" class="form-control form-control-lg bg-light" id="phone" name="phone" placeholder="VD: 0901234567">
                        </div>
                        <div class="d-grid mt-2">
                            <button type="submit" class="btn btn-primary btn-lg fw-medium shadow-sm">
                                <i class="bi bi-save me-1"></i> Lưu Thông Tin
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Modals Sửa Nhà Cung Cấp --}}
    @foreach ($suppliers as $supplier)
        <div class="modal fade" id="editSupplierModal{{ $supplier->id }}" tabindex="-1" aria-labelledby="editSupplierModalLabel{{ $supplier->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header border-bottom-0 pb-0">
                        <h5 class="modal-title fw-bold" id="editSupplierModalLabel{{ $supplier->id }}">Cập Nhật Nhà Cung Cấp</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body pb-4">
                        <form action="{{ route('admin.suppliers.update', $supplier->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-4">
                                <label for="name{{ $supplier->id }}" class="form-label fw-medium text-dark">Tên Nhà Cung Cấp <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-lg bg-light" id="name{{ $supplier->id }}" name="name" value="{{ $supplier->name }}" required>
                            </div>
                            <div class="mb-4">
                                <label for="phone{{ $supplier->id }}" class="form-label fw-medium text-dark">Số Điện Thoại</label>
                                <input type="text" class="form-control form-control-lg bg-light" id="phone{{ $supplier->id }}" name="phone" value="{{ $supplier->phone }}">
                            </div>
                            <div class="d-grid mt-2">
                                <button type="submit" class="btn btn-primary btn-lg fw-medium shadow-sm">
                                    <i class="bi bi-save me-1"></i> Lưu Cập Nhật
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection
