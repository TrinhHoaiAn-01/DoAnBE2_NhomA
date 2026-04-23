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

        // PHÂN TÍCH DIFF
        foreach ($logs as $log) {
            // So sánh dữ liệu (Diff Analysis)
            $changes = [];
            $oldData = is_string($log->old_data) ? json_decode($log->old_data, true) : $log->old_data;
            $newData = is_string($log->new_data) ? json_decode($log->new_data, true) : $log->new_data;

            if (is_array($oldData) && is_array($newData)) {
                foreach ($newData as $key => $newValue) {
                    $oldValue = $oldData[$key] ?? null;
                    
                    // Nếu là mảng (như phân quyền)
                    if (is_array($newValue) && is_array($oldValue)) {
                        foreach ($newValue as $field => $val) {
                            if (!in_array($field, ['updated_at', 'created_at']) && $val !== ($oldValue[$field] ?? null)) {
                                $changes[] = [
                                    'item' => $key,
                                    'field' => $field,
                                    'old' => $oldValue[$field] ?? null,
                                    'new' => $val
                                ];
                            }
                        }
                    } 
                    // Nếu là dữ liệu phẳng (phẳng 1 cấp)
                    elseif (!is_array($newValue) && !in_array($key, ['updated_at', 'created_at']) && $newValue !== $oldValue) {
                        $changes[] = [
                            'item' => 'Dữ liệu',
                            'field' => $key,
                            'old' => $oldValue,
                            'new' => $newValue
                        ];
                    }
                }
            }
            $log->changes_diff = $changes;
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
