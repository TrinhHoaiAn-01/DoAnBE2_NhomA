@extends('layouts.admin', ['title' => 'NeoMart Admin - Nguoi dung'])

@section('content')
    <div class="d-flex flex-wrap justify-content-between gap-3 align-items-start mb-4">
        <div>
            <p class="text-uppercase text-secondary small fw-semibold mb-2">Quan tri nguoi dung</p>
            <h1 class="h2 fw-bold mb-1">Tai khoan he thong</h1>
            <p class="text-secondary mb-0">Theo doi tai khoan, vai tro va trang thai truy cap.</p>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="soft-surface rounded-4 p-3">
                <div class="small text-secondary">Quan tri vien</div>
                <div class="fs-4 fw-bold">{{ $adminCount }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="soft-surface rounded-4 p-3">
                <div class="small text-secondary">Khach hang</div>
                <div class="fs-4 fw-bold">{{ $customerCount }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="soft-surface rounded-4 p-3">
                <div class="small text-secondary">Da khoa</div>
                <div class="fs-4 fw-bold">{{ $lockedCount }}</div>
            </div>
        </div>
    </div>

    <div class="surface rounded-4 p-3 p-lg-4 mb-4">
        <form class="row g-3 align-items-end">
            <div class="col-lg-5">
                <label class="form-label" for="search">Tim nguoi dung</label>
                <input class="form-control" id="search" name="search" value="{{ $search }}" placeholder="Ten, email hoac so dien thoai">
            </div>
            <div class="col-lg-3">
                <label class="form-label" for="role_id">Vai tro</label>
                <select class="form-select" id="role_id" name="role_id">
                    <option value="">Tat ca</option>
                    @foreach ($roleOptions as $value => $label)
                        <option value="{{ $value }}" @selected($roleId === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2">
                <label class="form-label" for="status">Trang thai</label>
                <select class="form-select" id="status" name="status">
                    <option value="">Tat ca</option>
                    @foreach ($statusOptions as $value => $label)
                        <option value="{{ $value }}" @selected($status === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2 d-flex gap-2">
                <button class="btn btn-primary flex-grow-1" type="submit">Loc</button>
                <a class="btn btn-outline-secondary" href="{{ route('admin.users.index') }}">Xoa</a>
            </div>
        </form>
    </div>

    <div class="surface rounded-4 p-4">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nguoi dung</th>
                        <th>So dien thoai</th>
                        <th>Vai tro</th>
                        <th>Trang thai</th>
                        <th>Ngay tao</th>
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
                            <td>{{ $roleOptions[$user->role_id] ?? 'Khac' }}</td>
                            <td>
                                <span class="badge {{ $user->status === 'locked' ? 'text-bg-danger' : 'text-bg-success' }}">
                                    {{ $statusOptions[$user->status] ?? $user->status }}
                                </span>
                            </td>
                            <td>{{ $user->created_at?->format('d/m/Y H:i') }}</td>
                            <td>
                                <form class="d-flex justify-content-end gap-2" method="post" action="{{ route('admin.users.update', $user) }}">
                                    @csrf
                                    @method('patch')
                                    <select class="form-select form-select-sm" name="role_id" style="max-width: 150px">
                                        @foreach ($roleOptions as $value => $label)
                                            <option value="{{ $value }}" @selected((int) $user->role_id === $value)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    <select class="form-select form-select-sm" name="status" style="max-width: 130px">
                                        @foreach ($statusOptions as $value => $label)
                                            <option value="{{ $value }}" @selected($user->status === $value)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    <button class="btn btn-sm btn-outline-primary" type="submit">Luu</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-secondary py-4">Chua co nguoi dung nao.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $users->links() }}</div>
    </div>
@endsection
