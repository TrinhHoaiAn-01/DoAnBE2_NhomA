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
                    <p class="text-muted mb-1 small fw-bold text-uppercase">Người dùng</p>
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
</div>

<div class="card border-0 shadow-sm rounded-3">
    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0 fw-bold">Thông báo từ Người 5 (Hệ thống)</h5>
        <span class="badge bg-primary rounded-pill text-uppercase px-3 py-2">Quan trọng</span>
    </div>
    <div class="card-body p-4">
        <div class="alert alert-primary border-0 border-start border-4 border-primary bg-primary bg-opacity-10 rounded-end-3" role="alert">
            <h5 class="alert-heading fw-bold"><i class="bi bi-info-circle-fill me-2"></i>Chào các bạn nhóm A!</h5>
            <p class="mb-0 mt-2">Khung Admin đã sẵn sàng và được chuyển đổi hoàn toàn sang <strong>Bootstrap 5</strong> theo cấu trúc của Trọng. Các bạn hãy pull code từ Master sau khi tôi merge để bắt đầu đắp các tính năng của mình vào nhé. Chúc chúng ta làm đồ án tốt!</p>
        </div>
    </div>
</div>
@endsection
