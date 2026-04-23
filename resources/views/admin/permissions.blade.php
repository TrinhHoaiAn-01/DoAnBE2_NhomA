@extends('layouts.admin')

@section('title', 'Phân quyền Hệ thống')

@section('content')
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <form action="{{ route('admin.permissions.update') }}" method="POST">
            @csrf
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0 fw-bold">Danh sách Quyền hạn (Nhóm A)</h5>
                <button type="submit" class="btn btn-primary btn-sm px-3">
                    <i class="bi bi-save me-1"></i> Lưu thay đổi
                </button>
            </div>

            @if(session('success'))
                <div
                    class="alert alert-success m-3 mb-0 border-0 border-start border-4 border-success bg-success bg-opacity-10">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                </div>
            @endif

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle mb-0">
                        <thead class="table-light text-center">
                            <tr>
                                <th class="text-start py-3" style="min-width: 200px;">Thành viên / Module</th>
                                <th class="py-3"><i class="bi bi-eye text-primary mb-1 d-block fs-5"></i> Xem</th>
                                <th class="py-3"><i class="bi bi-plus-circle text-success mb-1 d-block fs-5"></i> Thêm</th>
                                <th class="py-3"><i class="bi bi-pencil-square text-warning mb-1 d-block fs-5"></i> Sửa</th>
                                <th class="py-3"><i class="bi bi-trash text-danger mb-1 d-block fs-5"></i> Xóa</th>
                                <th class="py-3"><i class="bi bi-check-circle text-info mb-1 d-block fs-5"></i> Duyệt</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($roles as $role)
                                <tr>
                                    <td class="fw-bold text-start p-3">
                                        {{ $role->role_name }}
                                        <div class="text-muted small fw-normal">Module: {{ $role->module }}</div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-switch d-flex justify-content-center">
                                            <input type="hidden" name="permissions[{{ $role->id }}][can_view]" value="0">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                name="permissions[{{ $role->id }}][can_view]" value="1" {{ $role->can_view ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-switch d-flex justify-content-center">
                                            <input type="hidden" name="permissions[{{ $role->id }}][can_add]" value="0">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                name="permissions[{{ $role->id }}][can_add]" value="1" {{ $role->can_add ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-switch d-flex justify-content-center">
                                            <input type="hidden" name="permissions[{{ $role->id }}][can_edit]" value="0">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                name="permissions[{{ $role->id }}][can_edit]" value="1" {{ $role->can_edit ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-switch d-flex justify-content-center">
                                            <input type="hidden" name="permissions[{{ $role->id }}][can_delete]" value="0">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                name="permissions[{{ $role->id }}][can_delete]" value="1" {{ $role->can_delete ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-switch d-flex justify-content-center">
                                            <input type="hidden" name="permissions[{{ $role->id }}][can_approve]" value="0">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                name="permissions[{{ $role->id }}][can_approve]" value="1" {{ $role->can_approve ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white py-3 text-muted small">
                <i class="bi bi-info-circle me-1"></i> Bật/tắt công tắc để cấp hoặc thu hồi quyền truy cập chức năng. Chỉ có
                Người 5 (Quản trị hệ thống) mới có thể thực hiện thao tác này.
            </div>
        </form>
    </div>
@endsection