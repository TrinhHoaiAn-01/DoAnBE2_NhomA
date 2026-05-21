@extends('layouts.admin', ['title' => 'NeoMart Admin - Đánh giá'])

@section('content')
    <div class="d-flex flex-wrap justify-content-between gap-3 align-items-start mb-4">
        <div>
            <p class="text-uppercase text-secondary small fw-semibold mb-2">Đánh giá sản phẩm</p>
            <h1 class="h2 fw-bold mb-1">Duyệt phản hồi khách hàng</h1>
            <p class="text-secondary mb-0">Kiểm tra, duyệt hoặc ẩn đánh giá trước khi hiển thị ở trang sản phẩm.</p>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="soft-surface rounded-4 p-3">
                <div class="small text-secondary">Chờ duyệt</div>
                <div class="fs-4 fw-bold">{{ $pendingCount }}</div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="soft-surface rounded-4 p-3">
                <div class="small text-secondary">Đã duyệt</div>
                <div class="fs-4 fw-bold">{{ $approvedCount }}</div>
            </div>
        </div>
    </div>

    <div class="surface rounded-4 p-3 p-lg-4 mb-4">
        <form class="row g-3 align-items-end">
            <div class="col-lg-9">
                <label class="form-label" for="status">Trạng thái</label>
                <select class="form-select" id="status" name="status">
                    <option value="">Tất cả</option>
                    <option value="pending" @selected($status === 'pending')>Chờ duyệt</option>
                    <option value="approved" @selected($status === 'approved')>Đã duyệt</option>
                </select>
            </div>
            <div class="col-lg-3 d-flex gap-2">
                <button class="btn btn-primary flex-grow-1" type="submit">Lọc</button>
                <a class="btn btn-outline-secondary" href="{{ route('admin.reviews.index') }}">Bỏ lọc</a>
            </div>
        </form>
    </div>

    <div class="surface rounded-4 p-4">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Khách hàng</th>
                        <th>Sao</th>
                        <th>Nội dung</th>
                        <th>Trạng thái</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reviews as $review)
                        <tr>
                            <td>{{ $review->product?->name }}</td>
                            <td>{{ $review->customer_name }}</td>
                            <td class="text-warning fw-bold">{{ $review->rating }}/5</td>
                            <td>
                                <div class="fw-semibold">{{ $review->title ?: 'Không có tiêu đề' }}</div>
                                <div class="small text-secondary">{{ $review->content }}</div>
                            </td>
                            <td>
                                <span class="badge {{ $review->is_approved ? 'text-bg-success' : 'text-bg-warning' }}">
                                    {{ $review->is_approved ? 'Đã duyệt' : 'Chờ duyệt' }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <form method="post" action="{{ route('admin.reviews.update', $review) }}">
                                        @csrf
                                        @method('patch')
                                        <input type="hidden" name="is_approved" value="{{ $review->is_approved ? 0 : 1 }}">
                                        <button class="btn btn-sm btn-outline-primary" type="submit">{{ $review->is_approved ? 'Ẩn' : 'Duyệt' }}</button>
                                    </form>
                                    <form method="post" action="{{ route('admin.reviews.destroy', $review) }}">
                                        @csrf
                                        @method('delete')
                                        <button class="btn btn-sm btn-outline-danger" type="submit">Xóa</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-secondary py-4">Chưa có đánh giá nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $reviews->links() }}</div>
    </div>
@endsection
