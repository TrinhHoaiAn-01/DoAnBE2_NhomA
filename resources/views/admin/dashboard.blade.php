@extends('layouts.admin')

@section('title', 'Tổng quan hệ thống')

@section('content')
<div class="row g-4 mb-4">
    <!-- Card 1 -->
    <div class="col-12 col-md-6 col-lg-3">
        <div class="card h-100 border-0 shadow-sm rounded-3">
            <div class="card-body d-flex align-items-center">
                <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-3 me-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                    <i class="bi bi-people fs-3"></i>
                </div>
                <div>
                    <p class="text-muted mb-1 small fw-bold text-uppercase">Khách hàng</p>
                    <h3 class="mb-0 fw-bold">1,250</h3>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Card 2 -->
    <div class="col-12 col-md-6 col-lg-3">
        <div class="card h-100 border-0 shadow-sm rounded-3">
            <div class="card-body d-flex align-items-center">
                <div class="bg-success bg-opacity-10 text-success rounded-3 p-3 me-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                    <i class="bi bi-box-seam fs-3"></i>
                </div>
                <div>
                    <p class="text-muted mb-1 small fw-bold text-uppercase">Sản phẩm</p>
                    <h3 class="mb-0 fw-bold">452</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 3 -->
    <div class="col-12 col-md-6 col-lg-3">
        <div class="card h-100 border-0 shadow-sm rounded-3">
            <div class="card-body d-flex align-items-center">
                <div class="bg-warning bg-opacity-10 text-warning rounded-3 p-3 me-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                    <i class="bi bi-exclamation-triangle fs-3"></i>
                </div>
                <div>
                    <p class="text-muted mb-1 small fw-bold text-uppercase">Sắp hết hàng</p>
                    <h3 class="mb-0 fw-bold">15</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 4 -->
    <div class="col-12 col-md-6 col-lg-3">
        <div class="card h-100 border-0 shadow-sm rounded-3">
            <div class="card-body d-flex align-items-center">
                <div class="bg-info bg-opacity-10 text-info rounded-3 p-3 me-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                    <i class="bi bi-truck fs-3"></i>
                </div>
                <div>
                    <p class="text-muted mb-1 small fw-bold text-uppercase">Đơn hàng mới</p>
                    <h3 class="mb-0 fw-bold">89</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Cảnh báo hệ thống -->
    <div class="col-12 col-lg-8">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0 fw-bold">Hoạt động hệ thống gần đây</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <tbody>
                            <tr>
                                <td class="text-center" style="width: 50px;"><i class="bi bi-shield-check text-success fs-5"></i></td>
                                <td>
                                    <div class="fw-bold">Đình Hoàng</div>
                                    <div class="text-muted small">Vừa cập nhật ma trận phân quyền hệ thống</div>
                                </td>
                                <td class="text-end text-muted small pe-4">Vài giây trước</td>
                            </tr>
                            <tr>
                                <td class="text-center"><i class="bi bi-box-arrow-in-right text-primary fs-5"></i></td>
                                <td>
                                    <div class="fw-bold">Văn Trọng</div>
                                    <div class="text-muted small">Đăng nhập vào hệ thống quản trị</div>
                                </td>
                                <td class="text-end text-muted small pe-4">10 phút trước</td>
                            </tr>
                            <tr>
                                <td class="text-center"><i class="bi bi-cart-check text-info fs-5"></i></td>
                                <td>
                                    <div class="fw-bold">Hệ thống</div>
                                    <div class="text-muted small">Có 15 đơn hàng mới cần xử lý</div>
                                </td>
                                <td class="text-end text-muted small pe-4">1 giờ trước</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Tồn kho thấp -->
    <div class="col-12 col-lg-4">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0 fw-bold">Sản phẩm sắp hết</h5>
                <a href="#" class="btn btn-sm btn-outline-primary">Xem tất cả</a>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                        <div>
                            <h6 class="mb-0">Laptop Dell XPS 15</h6>
                            <small class="text-muted">Kho chính</small>
                        </div>
                        <span class="badge bg-danger rounded-pill">Chỉ còn 2</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                        <div>
                            <h6 class="mb-0">Chuột Logitech MX Master 3</h6>
                            <small class="text-muted">Kho phụ</small>
                        </div>
                        <span class="badge bg-warning rounded-pill">Chỉ còn 5</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                        <div>
                            <h6 class="mb-0">Bàn phím cơ Keychron K2</h6>
                            <small class="text-muted">Kho chính</small>
                        </div>
                        <span class="badge bg-warning rounded-pill">Chỉ còn 8</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
