<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function permissions()
    {
        $roles = \App\Models\RolePermission::all();
        return view('admin.permissions', compact('roles'));
    }

    public function logs(Request $request)
    {
        $query = \App\Models\SystemLog::query();

        // Sắp xếp
        $sort = $request->get('sort', 'latest');
        if ($sort == 'oldest') {
            $query->oldest();
        } else {
            $query->latest();
        }

        $logs = $query->paginate(20);

        // HÀM AI GIẢ LẬP: Đánh giá rủi ro dựa trên từ khóa (Keyword-based Semantic Analysis)
        foreach ($logs as $log) {
            $action = mb_strtolower($log->action);
            if (str_contains($action, 'xóa') || str_contains($action, 'quyền')) {
                $log->ai_risk = 'Rủi ro cao';
                $log->ai_color = 'danger';
            } elseif (str_contains($action, 'cập nhật') || str_contains($action, 'sửa')) {
                $log->ai_risk = 'Trung bình';
                $log->ai_color = 'warning';
            } else {
                $log->ai_risk = 'An toàn';
                $log->ai_color = 'success';
            }
        }

        return view('admin.logs', compact('logs', 'sort'));
    }

    public function updatePermissions(Request $request)
    {
        $permissions = $request->input('permissions', []);
        $oldData = [];
        $newData = [];

        foreach ($permissions as $roleId => $data) {
            $role = \App\Models\RolePermission::find($roleId);
            if ($role) {
                // Lưu lại dữ liệu cũ trước khi sửa
                $oldData[$role->role_name] = $role->toArray();
                
                // Cập nhật dữ liệu mới
                $role->update([
                    'can_view' => isset($data['can_view']) ? $data['can_view'] : 0,
                    'can_add' => isset($data['can_add']) ? $data['can_add'] : 0,
                    'can_edit' => isset($data['can_edit']) ? $data['can_edit'] : 0,
                    'can_delete' => isset($data['can_delete']) ? $data['can_delete'] : 0,
                    'can_approve' => isset($data['can_approve']) ? $data['can_approve'] : 0,
                ]);

                // Lưu lại dữ liệu sau khi sửa
                $newData[$role->role_name] = $role->toArray();
            }
        }

        // Ghi vào Nhật ký hệ thống (Task 50)
        \App\Models\SystemLog::create([
            'user_name' => 'Người 5 (Quản trị viên)',
            'action' => 'Cập nhật Phân quyền hệ thống',
            'target_type' => 'Phân quyền Hệ thống',
            'old_data' => $oldData,
            'new_data' => $newData,
        ]);

        return redirect()->back()->with('success', 'Đã lưu Phân quyền hệ thống và ghi vào Nhật ký Hệ thống thành công!');
    }
}
