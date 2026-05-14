@extends('layouts.admin', ['title' => 'Trung tâm trợ giúp (FAQ)'])

@section('content')
    <div class="d-flex flex-wrap justify-content-between gap-3 align-items-start mb-4">
        <div>
            <p class="text-uppercase text-secondary small fw-semibold mb-2">Hỗ trợ & Nội dung</p>
            <h1 class="h2 fw-bold mb-1">Trung tâm trợ giúp (FAQ)</h1>
            <p class="text-secondary mb-0">Quản lý các câu hỏi thường gặp của khách hàng.</p>
        </div>
        <div>
            <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addFaqModal">
                <i class="bi bi-plus-circle me-1"></i> Thêm câu hỏi mới
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success"><i class="bi bi-check-circle me-1"></i> {{ session('success') }}</div>
    @endif

    <div class="surface p-4">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="10%">Ưu tiên</th>
                        <th width="15%">Danh mục</th>
                        <th width="45%">Câu hỏi & Trả lời</th>
                        <th width="15%" class="text-center">Trạng thái</th>
                        <th width="15%"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($faqs as $faq)
                        <tr>
                            <td class="text-center fw-bold">{{ $faq->sort_order }}</td>
                            <td><span class="badge bg-info text-dark">{{ $faq->category }}</span></td>
                            <td>
                                <div class="fw-bold mb-1">{{ $faq->question }}</div>
                                <div class="text-muted small text-truncate" style="max-width: 400px;">{{ $faq->answer }}</div>
                            </td>
                            <td class="text-center">
                                <form action="{{ route('admin.faqs.toggle', $faq->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm {{ $faq->is_active ? 'btn-success' : 'btn-secondary' }}">
                                        {!! $faq->is_active ? '<i class="bi bi-eye"></i> Đang hiện' : '<i class="bi bi-eye-slash"></i> Đã ẩn' !!}
                                    </button>
                                </form>
                            </td>
                            <td class="text-end">
                                <form action="{{ route('admin.faqs.destroy', $faq->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa câu hỏi này?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i> Xóa</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">Chưa có dữ liệu FAQ.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Thêm FAQ -->
    <div class="modal fade" id="addFaqModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <form class="modal-content" action="{{ route('admin.faqs.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Thêm Câu Hỏi Mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Danh mục (Ví dụ: Thanh toán, Giao hàng)</label>
                            <input type="text" name="category" class="form-control" required placeholder="Nhập danh mục...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Độ ưu tiên (Số nhỏ xếp trên)</label>
                            <input type="number" name="sort_order" class="form-control" value="0" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Câu hỏi</label>
                            <input type="text" name="question" class="form-control" required placeholder="Nhập câu hỏi...">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Câu trả lời</label>
                            <textarea name="answer" class="form-control" rows="5" required placeholder="Nhập nội dung trả lời chi tiết..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Lưu câu hỏi</button>
                </div>
            </form>
        </div>
    </div>
@endsection
