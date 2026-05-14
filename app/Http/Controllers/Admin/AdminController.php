<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard()
    {
        $usersCount = \App\Models\User::count();
        $productsCount = \App\Models\Product::count();
        $lowStockCount = \App\Models\Product::where('stock', '<=', 10)->count();
        $pendingOrdersCount = \App\Models\Order::where('status', 'pending')->count();
        
        $lowStockProducts = \App\Models\Product::where('stock', '<=', 10)->orderBy('stock', 'asc')->take(5)->get();
        $recentLogs = \App\Models\SystemLog::latest()->take(5)->get();

        // Thống kê doanh thu 7 ngày gần nhất
        $dates = collect();
        $revenues = collect();
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $revenue = \App\Models\Order::whereDate('created_at', $date)
                        ->where('status', '!=', 'cancelled')
                        ->sum('total');
            $dates->push(now()->subDays($i)->format('d/m'));
            $revenues->push((float)$revenue);
        }

        return view('admin.dashboard', compact(
            'usersCount', 'productsCount', 'lowStockCount', 'pendingOrdersCount', 
            'lowStockProducts', 'recentLogs', 'dates', 'revenues'
        ));
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
            'user_name' => Auth::user()->name ?? 'Quản trị viên',
            'action' => 'Cập nhật Phân quyền hệ thống',
            'target_type' => 'Phân quyền Hệ thống',
            'old_data' => $oldData,
            'new_data' => $newData,
        ]);

        return redirect()->back()->with('success', 'Đã lưu Phân quyền hệ thống và ghi vào Nhật ký Hệ thống thành công!');
    }
}
