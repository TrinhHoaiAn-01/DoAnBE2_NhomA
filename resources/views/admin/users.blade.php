@extends('layouts.admin', ['title' => 'NeoMart Admin - Người dùng'])

@section('content')
    <div class="d-flex flex-wrap justify-content-between gap-3 align-items-start mb-4">
        <div>
            <p class="text-uppercase text-secondary small fw-semibold mb-2">Quản trị người dùng</p>
            <h1 class="h2 fw-bold mb-1">Tài khoản hệ thống</h1>
            <p class="text-secondary mb-0">Theo dõi tài khoản, vai trò và trạng thái truy cập.</p>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="soft-surface rounded-4 p-3">
                <div class="small text-secondary">Quản trị viên</div>
                <div class="fs-4 fw-bold">{{ $adminCount }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="soft-surface rounded-4 p-3">
                <div class="small text-secondary">Khách hàng</div>
                <div class="fs-4 fw-bold">{{ $customerCount }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="soft-surface rounded-4 p-3">
                <div class="small text-secondary">Đã khóa</div>
                <div class="fs-4 fw-bold">{{ $lockedCount }}</div>
            </div>
        </div>
    </div>

    <div class="surface rounded-4 p-3 p-lg-4 mb-4">
        <form class="row g-3 align-items-end">
            <div class="col-lg-5">
                <label class="form-label" for="search">Tìm người dùng</label>
                <input class="form-control" id="search" name="search" value="{{ $search }}" placeholder="Tên, email hoặc số điện thoại">
            </div>
            <div class="col-lg-3">
                <label class="form-label" for="role_id">Vai trò</label>
                <select class="form-select" id="role_id" name="role_id">
                    <option value="">Tất cả</option>
                    @foreach ($roleOptions as $value => $label)
                        <option value="{{ $value }}" @selected($roleId === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2">
                <label class="form-label" for="status">Trạng thái</label>
                <select class="form-select" id="status" name="status">
                    <option value="">Tất cả</option>
                    @foreach ($statusOptions as $value => $label)
                        <option value="{{ $value }}" @selected($status !== null && $status !== '' && (int)$status === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2 d-flex gap-2">
                <button class="btn btn-primary flex-grow-1" type="submit">Lọc</button>
                <a class="btn btn-outline-secondary" href="{{ route('admin.users.index') }}">Xóa</a>
            </div>
        </form>
    </div>

    <div class="surface rounded-4 p-4">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Người dùng</th>
                        <th>Số điện thoại</th>
                        <th>Vai trò</th>
                        <th>Trạng thái</th>
                        <th>Ngày tạo</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $user->name }}</div>
                                <div class="small text-secondary">{{ $user->email }}</div>
                            </td>
                            <td>{{ $user->phone ?: '-' }}</td>
                            <td>{{ $roleOptions[$user->role_id] ?? 'Khác' }}</td>
                            <td>
                                <span class="badge {{ !$user->status ? 'text-bg-danger' : 'text-bg-success' }}">
                                    {{ $user->status ? 'Đang hoạt động' : 'Đã khóa' }}
                                </span>
                            </td>
                            <td>{{ $user->created_at?->format('d/m/Y H:i') }}</td>
                            <td>
                                <form class="d-flex justify-content-end gap-2" method="post" action="{{ route('admin.users.update', $user) }}">
                                    @csrf
                                    @method('patch')
                                    <input type="hidden" name="_record_updated_at" value="{{ $user->updated_at?->getTimestamp() }}">
                                    <select class="form-select form-select-sm" name="role_id" style="max-width: 150px">
                                        @foreach ($roleOptions as $value => $label)
                                            <option value="{{ $value }}" @selected((int) $user->role_id === $value)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    <select class="form-select form-select-sm" name="status" style="max-width: 130px">
                                        @foreach ($statusOptions as $value => $label)
                                            <option value="{{ $value }}" @selected((int)$user->status === $value)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    <button class="btn btn-sm btn-outline-primary" type="submit">Lưu</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-secondary py-4">Chưa có người dùng nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $users->links() }}</div>
    </div>
@endsection
