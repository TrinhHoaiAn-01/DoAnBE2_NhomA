<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\SystemLog;
use App\Models\RolePermission;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    private function orderStatusLabels(): array
    {
        return [
            'pending' => 'Chờ xử lý',
            'processing' => 'Đang xử lý',
            'shipping' => 'Đang giao',
            'completed' => 'Hoàn thành',
            'cancelled' => 'Đã hủy',
        ];
    }

    private function payableOrderQuery()
    {
        // Don hang bi huy khong tinh vao doanh thu va bao cao ban hang.
        return Order::query()->where('status', '!=', 'cancelled');
    }

    public function dashboard()
    {
        $usersCount = User::count();
        $productsCount = Product::count();
        $ordersCount = Order::count();
        $lowStockCount = Product::where('stock', '<=', 10)->count();
        $outOfStockCount = Product::where('stock', 0)->count();
        $stockValue = Product::query()
            ->selectRaw('SUM(price * stock) as total_value')
            ->value('total_value') ?? 0;
        $pendingOrdersCount = Order::where('status', 'pending')->count();
        $completedOrdersCount = Order::where('status', 'completed')->count();
        $todayRevenue = (clone $this->payableOrderQuery())
            ->whereDate('created_at', today())
            ->sum('total');
        $monthRevenue = (clone $this->payableOrderQuery())
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->sum('total');
        $orderStatusCounts = Order::query()
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');
        $orderStatusStats = collect($this->orderStatusLabels())
            ->map(function (string $label, string $status) use ($orderStatusCounts) {
                return [
                    'status' => $status,
                    'label' => $label,
                    'total' => (int) ($orderStatusCounts[$status] ?? 0),
                ];
            })
            ->values();
        
        $lowStockProducts = Product::where('stock', '<=', 10)
            ->orderBy('stock', 'asc')
            ->take(5)
            ->get();
        $recentOrders = Order::query()
            ->latest()
            ->take(5)
            ->get();
        $recentLogs = SystemLog::latest()->take(5)->get();

        // Thong ke doanh thu 7 ngay gan nhat.
        $dates = collect();
        $revenues = collect();
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $revenue = Order::whereDate('created_at', $date)
                        ->where('status', '!=', 'cancelled')
                        ->sum('total');
            $dates->push(now()->subDays($i)->format('d/m'));
            $revenues->push((float)$revenue);
        }
        $weeklyRevenueTotal = $revenues->sum();
        $averageDailyRevenue = $revenues->avg() ?? 0;
        $dates = $dates->values()->all();
        $revenues = $revenues
            ->map(fn ($value) => (float) $value)
            ->values()
            ->all();

        return view('admin.dashboard', compact(
            'usersCount', 'productsCount', 'ordersCount', 'lowStockCount', 'pendingOrdersCount',
            'completedOrdersCount', 'outOfStockCount', 'stockValue', 'todayRevenue', 'monthRevenue',
            'weeklyRevenueTotal', 'averageDailyRevenue', 'orderStatusStats',
            'lowStockProducts', 'recentOrders', 'recentLogs', 'dates', 'revenues'
        ));
    }

    public function permissions()
    {
        $roles = RolePermission::all();
        return view('admin.permissions', compact('roles'));
    }

    public function logs(Request $request)
    {
        $query = SystemLog::query();

        // Sap xep ban ghi nhat ky theo lua chon cua nguoi dung.
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
            $role = RolePermission::find($roleId);
            if ($role) {
                // Luu lai du lieu cu truoc khi sua.
                $oldData[$role->role_name] = $role->toArray();
                
                // Cap nhat du lieu moi.
                $role->update([
                    'can_view' => isset($data['can_view']) ? $data['can_view'] : 0,
                    'can_add' => isset($data['can_add']) ? $data['can_add'] : 0,
                    'can_edit' => isset($data['can_edit']) ? $data['can_edit'] : 0,
                    'can_delete' => isset($data['can_delete']) ? $data['can_delete'] : 0,
                    'can_approve' => isset($data['can_approve']) ? $data['can_approve'] : 0,
                ]);

                // Luu lai du lieu sau khi sua.
                $newData[$role->role_name] = $role->toArray();
            }
        }

        // Ghi vao nhat ky he thong (Task 50).
        SystemLog::create([
            'user_name' => Auth::user()->name ?? 'Quản trị viên',
            'action' => 'Cập nhật Phân quyền hệ thống',
            'target_type' => 'Phân quyền Hệ thống',
            'old_data' => $oldData,
            'new_data' => $newData,
        ]);

        return redirect()->back()->with('success', 'Đã lưu Phân quyền hệ thống và ghi vào Nhật ký Hệ thống thành công!');
    }
}
