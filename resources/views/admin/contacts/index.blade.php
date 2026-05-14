@extends('layouts.admin', ['title' => 'Liên hệ từ khách hàng'])

@section('content')
    <div class="d-flex flex-wrap justify-content-between gap-3 align-items-start mb-4">
        <div>
            <p class="text-uppercase text-secondary small fw-semibold mb-2">Hỗ trợ khách hàng</p>
            <h1 class="h2 fw-bold mb-1">Danh sách Liên hệ</h1>
            <p class="text-secondary mb-0">Tiếp nhận và xử lý yêu cầu, khiếu nại từ khách hàng.</p>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="soft-surface rounded-4 p-3 border-start border-warning border-4">
                <div class="small text-secondary fw-semibold">Chờ xử lý</div>
                <div class="fs-4 fw-bold text-warning">{{ $pendingCount }}</div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="soft-surface rounded-4 p-3 border-start border-success border-4">
                <div class="small text-secondary fw-semibold">Đã giải quyết</div>
                <div class="fs-4 fw-bold text-success">{{ $resolvedCount }}</div>
            </div>
        </div>
    </div>

    <div class="surface p-4">
        <div class="d-flex gap-2 mb-4">
            <a href="{{ route('admin.contacts.index') }}" class="btn {{ !request('status') ? 'btn-primary' : 'btn-outline-secondary' }}">Tất cả</a>
            <a href="{{ route('admin.contacts.index', ['status' => 'pending']) }}" class="btn {{ request('status') == 'pending' ? 'btn-warning text-dark' : 'btn-outline-warning text-dark' }}">Chờ xử lý</a>
            <a href="{{ route('admin.contacts.index', ['status' => 'resolved']) }}" class="btn {{ request('status') == 'resolved' ? 'btn-success' : 'btn-outline-success' }}">Đã giải quyết</a>
        </div>

        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Khách hàng</th>
                        <th>Chủ đề</th>
                        <th>Trạng thái</th>
                        <th>Ngày gửi</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($contacts as $contact)
                        <tr>
                            <td>
                                <div class="fw-bold">{{ $contact->name }}</div>
                                <div class="small text-muted">{{ $contact->email }}</div>
                            </td>
                            <td>
                                <span class="badge bg-secondary mb-1">{{ $contact->subject }}</span>
                                <div class="text-truncate" style="max-width: 250px;">{{ $contact->message }}</div>
                            </td>
                            <td>
                                @if($contact->status == 'pending')
                                    <span class="badge bg-warning text-dark px-2 py-1"><i class="bi bi-hourglass-split"></i> Chờ xử lý</span>
                                @else
                                    <span class="badge bg-success px-2 py-1"><i class="bi bi-check-circle"></i> Đã giải quyết</span>
                                @endif
                            </td>
                            <td>{{ $contact->created_at->format('d/m/Y H:i') }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.contacts.show', $contact->id) }}" class="btn btn-sm btn-outline-primary">Chi tiết & Trả lời</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Chưa có liên hệ nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $contacts->links() }}</div>
    </div>
@endsection
