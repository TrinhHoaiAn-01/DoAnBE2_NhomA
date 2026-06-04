@extends('layouts.admin', ['title' => 'Quản lý Banner'])

@section('content')
    <div class="d-flex flex-wrap justify-content-between gap-3 align-items-start mb-4">
        <div>
            <p class="text-uppercase text-secondary small fw-semibold mb-2">Nội dung trang chủ</p>
            <h1 class="h2 fw-bold mb-1">Quản lý Banner</h1>
            <p class="text-secondary mb-0">Thiết lập các hình ảnh quảng cáo, sự kiện trên trang chủ.</p>
        </div>
        <div>
            <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addBannerModal">
                <i class="bi bi-cloud-upload me-1"></i> Đăng Banner mới
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success"><i class="bi bi-check-circle me-1"></i> {{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">Có lỗi xảy ra khi tải ảnh lên, vui lòng kiểm tra lại (chỉ hỗ trợ định dạng ảnh, dung lượng < 2MB).</div>
    @endif

    <div class="surface p-4">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="15%">Hình ảnh</th>
                        <th width="25%">Tiêu đề & Link</th>
                        <th width="15%">Vị trí / Thứ tự</th>
                        <th width="20%">Thời gian hiển thị</th>
                        <th width="15%" class="text-center">Trạng thái</th>
                        <th width="10%"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($banners as $banner)
                        <tr>
                            <td>
                                <img src="{{ asset($banner->image_url) }}" alt="Banner" class="rounded shadow-sm img-fluid" style="max-height: 80px; object-fit: cover;">
                            </td>
                            <td>
                                <div class="fw-bold">{{ $banner->title }}</div>
                                @if($banner->link)
                                    <a href="{{ $banner->link }}" target="_blank" class="small text-decoration-none"><i class="bi bi-link-45deg"></i> Xem Link đích</a>
                                @else
                                    <span class="small text-muted">Không có link</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-dark">{{ $banner->position }}</span>
                                <div class="small mt-1">Ưu tiên: <b>{{ $banner->sort_order }}</b></div>
                            </td>
                            <td>
                                @if($banner->start_date || $banner->end_date)
                                    <div class="small text-muted"><i class="bi bi-calendar-event"></i> Từ: {{ $banner->start_date ? $banner->start_date->format('d/m/Y') : 'Không giới hạn' }}</div>
                                    <div class="small text-muted"><i class="bi bi-calendar-event"></i> Đến: {{ $banner->end_date ? $banner->end_date->format('d/m/Y') : 'Không giới hạn' }}</div>
                                @else
                                    <span class="small text-success">Hiển thị vĩnh viễn</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <form action="{{ route('admin.banners.toggle', $banner->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="_record_updated_at" value="{{ $banner->updated_at?->getTimestamp() }}">
                                    <button type="submit" class="btn btn-sm {{ $banner->is_active ? 'btn-success' : 'btn-secondary' }}">
                                        {!! $banner->is_active ? '<i class="bi bi-eye"></i> Đang hiện' : '<i class="bi bi-eye-slash"></i> Đã ẩn' !!}
                                    </button>
                                </form>
                            </td>
                            <td class="text-end">
                                <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST" onsubmit="return confirm('Xóa banner này?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Chưa có Banner nào được đăng.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Thêm Banner -->
    <div class="modal fade" id="addBannerModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <form class="modal-content" action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Tải lên Banner mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Hình ảnh Banner <span class="text-danger">*</span></label>
                            <input type="file" name="image" class="form-control" accept="image/*" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Tiêu đề Banner <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" required placeholder="Ví dụ: Siêu Sale Tháng 5">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Đường dẫn khi click (Link đích)</label>
                            <input type="url" name="link" class="form-control" placeholder="https://...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Vị trí hiển thị</label>
                            <select name="position" class="form-select">
                                <option value="main_slider">Main Slider (Trang chủ)</option>
                                <option value="sidebar">Sidebar (Cột bên phải)</option>
                                <option value="footer">Footer</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Độ ưu tiên (Số nhỏ hiển thị trước)</label>
                            <input type="number" name="sort_order" class="form-control" value="0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Ngày bắt đầu hiển thị</label>
                            <input type="date" name="start_date" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Ngày kết thúc hiển thị</label>
                            <input type="date" name="end_date" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-cloud-upload"></i> Đăng tải Banner</button>
                </div>
            </form>
        </div>
    </div>
@endsection
