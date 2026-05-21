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
use Carbon\Carbon;
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
        $topProducts = OrderItem::query()
            ->select(
                'product_name',
                'sku',
                DB::raw('SUM(quantity) as sold_quantity'),
                DB::raw('SUM(subtotal) as sold_revenue')
            )
            ->whereHas('order', function ($query): void {
                $query->where('status', '!=', 'cancelled');
            })
            ->groupBy('product_name', 'sku')
            ->orderByDesc('sold_quantity')
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
            'lowStockProducts', 'recentOrders', 'topProducts', 'recentLogs', 'dates', 'revenues'
        ));
    }

    public function statistics(Request $request)
    {
        $filters = $request->validate([
            'from_date' => ['nullable', 'date'],
            'to_date' => ['nullable', 'date'],
            'period' => ['nullable', 'integer', 'in:7,30,90'],
        ]);
        $period = (int) ($filters['period'] ?? 7);

        $fromDate = isset($filters['from_date'])
            ? Carbon::parse($filters['from_date'])->startOfDay()
            : now()->subDays($period - 1)->startOfDay();
        $toDate = isset($filters['to_date'])
            ? Carbon::parse($filters['to_date'])->endOfDay()
            : now()->endOfDay();

        if ($fromDate->greaterThan($toDate)) {
            [$fromDate, $toDate] = [$toDate->copy()->startOfDay(), $fromDate->copy()->endOfDay()];
        }

        $ordersQuery = $this->payableOrderQuery()
            ->whereBetween('created_at', [$fromDate, $toDate]);

        $ordersCount = (clone $ordersQuery)->count();
        $totalRevenue = (clone $ordersQuery)->sum('total');
        $averageOrderValue = $ordersCount > 0 ? $totalRevenue / $ordersCount : 0;
        $paidOrdersCount = (clone $ordersQuery)->where('payment_status', 'paid')->count();
        $pendingPaymentCount = (clone $ordersQuery)->where('payment_status', 'pending')->count();
        $completedRevenue = (clone $ordersQuery)
            ->where('status', 'completed')
            ->sum('total');
        $dailyRevenueRows = (clone $ordersQuery)
            ->selectRaw('DATE(created_at) as report_date, SUM(total) as revenue')
            ->groupBy('report_date')
            ->orderBy('report_date')
            ->pluck('revenue', 'report_date');
        $dailyLabels = [];
        $dailyRevenue = [];
        $cursor = $fromDate->copy();

        while ($cursor->lte($toDate)) {
            $key = $cursor->format('Y-m-d');
            $dailyLabels[] = $cursor->format('d/m');
            $dailyRevenue[] = (float) ($dailyRevenueRows[$key] ?? 0);
            $cursor->addDay();
        }

        $bestSellingProducts = OrderItem::query()
            ->select(
                'product_name',
                'sku',
                DB::raw('SUM(quantity) as sold_quantity'),
                DB::raw('SUM(subtotal) as sold_revenue')
            )
            ->whereHas('order', function ($query) use ($fromDate, $toDate): void {
                $query->where('status', '!=', 'cancelled')
                    ->whereBetween('created_at', [$fromDate, $toDate]);
            })
            ->groupBy('product_name', 'sku')
            ->orderByDesc('sold_quantity')
            ->take(10)
            ->get();
        $paymentStats = (clone $ordersQuery)
            ->select('payment_method', DB::raw('COUNT(*) as total_orders'), DB::raw('SUM(total) as total_revenue'))
            ->groupBy('payment_method')
            ->orderByDesc('total_orders')
            ->get();

        return view('admin.statistics', compact(
            'fromDate',
            'toDate',
            'period',
            'ordersCount',
            'totalRevenue',
            'averageOrderValue',
            'paidOrdersCount',
            'pendingPaymentCount',
            'completedRevenue',
            'dailyLabels',
            'dailyRevenue',
            'bestSellingProducts',
            'paymentStats'
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
