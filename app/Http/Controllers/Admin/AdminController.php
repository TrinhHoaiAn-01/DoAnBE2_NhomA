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

    public function updatePermissions(Request $request)
    {
        $data = $request->input('permissions', []);
        
        foreach ($data as $roleId => $perms) {
            $role = \App\Models\RolePermission::find($roleId);
            if ($role) {
                // Lưu lại log trước khi update
                \App\Models\SystemLog::create([
                    'user_name' => 'Người 5 (Hệ thống)',
                    'action' => 'Cập nhật phân quyền',
                    'target_type' => 'Role: ' . $role->role_name,
                    'old_data' => $role->toArray(),
                    'new_data' => $perms,
                ]);

                // Update
                $role->update($perms);
            }
        }

        return redirect()->back()->with('success', 'Cập nhật phân quyền thành công! Đã ghi log hệ thống.');
    }

    public function logs()
    {
        return "Trang Nhật ký hệ thống đang được xây dựng (Người 5)";
    }
}
