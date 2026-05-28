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

/**
 * Controller AdminController
 *
 * Quản lý các chức năng cốt lõi của bảng điều khiển (Dashboard) dành cho Quản trị viên (Admin),
 * bao gồm: Xem thống kê tổng quan, báo cáo doanh thu chi tiết, báo cáo bán hàng của sản phẩm,
 * quản lý phân quyền (Role Permissions) và quản lý nhật ký hoạt động hệ thống (System Logs).
 */
class AdminController extends Controller
{
    /**
     * Lấy danh sách các nhãn trạng thái đơn hàng dịch sang tiếng Việt.
     *
     * @return array
     */
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

    /**
     * Tạo câu truy vấn cơ bản cho các đơn hàng hợp lệ (không bao gồm đơn hàng bị hủy).
     * Phục vụ mục đích tính doanh thu và các báo cáo tài chính.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function payableOrderQuery()
    {
        // Đơn hàng bị hủy sẽ không được tính vào doanh thu và báo cáo bán hàng
        return Order::query()->where('status', '!=', 'cancelled');
    }

    /**
     * Truy vấn thông tin doanh số sản phẩm bán ra trong một khoảng thời gian nhất định.
     *
     * @param \Carbon\Carbon $fromDate Ngày bắt đầu lọc
     * @param \Carbon\Carbon $toDate Ngày kết thúc lọc
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function soldProductReportQuery(Carbon $fromDate, Carbon $toDate)
    {
        // Nhóm doanh số theo tên sản phẩm và mã SKU từ các đơn hàng hợp lệ trong khoảng thời gian lọc
        return OrderItem::query()
            ->select(
                'product_name',
                'sku',
                DB::raw('SUM(quantity) as sold_quantity'), // Tổng số lượng đã bán
                DB::raw('SUM(subtotal) as sold_revenue')   // Tổng doanh thu thu về
            )
            ->whereHas('order', function ($query) use ($fromDate, $toDate): void {
                $query->where('status', '!=', 'cancelled')
                    ->whereBetween('created_at', [$fromDate, $toDate]);
            })
            ->groupBy('product_name', 'sku');
    }

    /**
     * Báo cáo doanh thu chi tiết theo khoảng thời gian và nhóm dữ liệu (ngày, tháng, năm).
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function revenueReport(Request $request)
    {
        // 1. Xác thực tham số lọc từ Request
        $filters = $request->validate([
            'from_date' => ['nullable', 'date'],
            'to_date' => ['nullable', 'date'],
            'group_by' => ['nullable', 'in:day,month,year'], // Nhóm theo ngày, tháng, hoặc năm
        ]);
        $groupBy = $filters['group_by'] ?? 'day';
        
        // Mặc định lọc doanh thu trong vòng 30 ngày gần nhất nếu không truyền ngày lọc cụ thể
        $fromDate = isset($filters['from_date'])
            ? Carbon::parse($filters['from_date'])->startOfDay()
            : now()->subDays(29)->startOfDay();
        $toDate = isset($filters['to_date'])
            ? Carbon::parse($filters['to_date'])->endOfDay()
            : now()->endOfDay();

        // Xử lý nếu người dùng chọn ngày bắt đầu lớn hơn ngày kết thúc
        if ($fromDate->greaterThan($toDate)) {
            [$fromDate, $toDate] = [$toDate->copy()->startOfDay(), $fromDate->copy()->endOfDay()];
        }

        // 2. Truy vấn dữ liệu đơn hàng trong khoảng thời gian lọc
        $ordersQuery = $this->payableOrderQuery()
            ->whereBetween('created_at', [$fromDate, $toDate]);

        // Lấy nhanh các chỉ số tổng quan cho màn hình báo cáo doanh thu
        $ordersCount = (clone $ordersQuery)->count(); // Tổng số đơn hàng hợp lệ
        $totalRevenue = (clone $ordersQuery)->sum('total'); // Tổng doanh thu dự kiến
        $paidOrdersCount = (clone $ordersQuery)
            ->where('payment_status', 'paid')
            ->count(); // Số lượng đơn đã thanh toán thành công
        $pendingPaymentCount = (clone $ordersQuery)
            ->where('payment_status', 'pending')
            ->count(); // Số lượng đơn đang chờ thanh toán
        $completedRevenue = (clone $ordersQuery)
            ->where('status', 'completed')
            ->sum('total'); // Doanh thu thực tế từ các đơn hàng đã giao thành công
        
        // Giá trị trung bình trên một đơn hàng (AOV)
        $averageOrderValue = $ordersCount > 0 ? $totalRevenue / $ordersCount : 0;
        
        // Nhóm doanh thu đơn hàng để vẽ biểu đồ
        $chartRows = (clone $ordersQuery)
            ->get(['created_at', 'total'])
            ->groupBy(function (Order $order) use ($groupBy): string {
                return match ($groupBy) {
                    'month' => $order->created_at->format('Y-m'),
                    'year' => $order->created_at->format('Y'),
                    default => $order->created_at->format('Y-m-d'),
                };
            })
            ->map(fn ($orders) => (float) $orders->sum('total'));

        // 3. Chuẩn bị trục tọa độ và nhãn hiển thị cho biểu đồ
        $revenueLabels = [];
        $revenueData = [];
        $revenueRows = [];
        
        // Sử dụng một cursor để duyệt qua từng mốc thời gian từ $fromDate đến $toDate và điền dữ liệu
        $cursor = match ($groupBy) {
            'month' => $fromDate->copy()->startOfMonth(),
            'year' => $fromDate->copy()->startOfYear(),
            default => $fromDate->copy(),
        };

        while ($cursor->lte($toDate)) {
            $key = match ($groupBy) {
                'month' => $cursor->format('Y-m'),
                'year' => $cursor->format('Y'),
                default => $cursor->format('Y-m-d'),
            };
            $label = match ($groupBy) {
                'month' => $cursor->format('m/Y'),
                'year' => $cursor->format('Y'),
                default => $cursor->format('d/m'),
            };
            $revenueLabels[] = $label;
            $revenue = (float) ($chartRows[$key] ?? 0);
            $revenueData[] = $revenue;
            $revenueRows[] = [
                'label' => $label,
                'revenue' => $revenue,
            ];

            // Tăng mốc thời gian tương ứng với chế độ nhóm
            match ($groupBy) {
                'month' => $cursor->addMonth(),
                'year' => $cursor->addYear(),
                default => $cursor->addDay(),
            };
        }

        return view('admin.reports.revenue', compact(
            'fromDate',
            'toDate',
            'groupBy',
            'ordersCount',
            'totalRevenue',
            'paidOrdersCount',
            'pendingPaymentCount',
            'completedRevenue',
            'averageOrderValue',
            'revenueLabels',
            'revenueData',
            'revenueRows'
        ));
    }

    /**
     * Báo cáo phân tích doanh số của các mặt hàng sản phẩm (Bán chạy và Bán chậm).
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function productSalesReport(Request $request)
    {
        // 1. Validate tham số
        $filters = $request->validate([
            'from_date' => ['nullable', 'date'],
            'to_date' => ['nullable', 'date'],
            'limit' => ['nullable', 'integer', 'min:5', 'max:30'], // Giới hạn số lượng bản ghi hiển thị
        ]);
        $fromDate = isset($filters['from_date'])
            ? Carbon::parse($filters['from_date'])->startOfDay()
            : now()->subDays(29)->startOfDay();
        $toDate = isset($filters['to_date'])
            ? Carbon::parse($filters['to_date'])->endOfDay()
            : now()->endOfDay();
        $limit = (int) ($filters['limit'] ?? 10);

        if ($fromDate->greaterThan($toDate)) {
            [$fromDate, $toDate] = [$toDate->copy()->startOfDay(), $fromDate->copy()->endOfDay()];
        }

        // 2. Lấy những sản phẩm có số lượng bán cao nhất (Best Selling) từ các đơn hàng hợp lệ
        $bestSellingProducts = $this->soldProductReportQuery($fromDate, $toDate)
            ->orderByDesc('sold_quantity')
            ->take($limit)
            ->get();

        // 3. Lấy những sản phẩm bán chậm nhất hoặc không bán được trong khoảng thời gian lọc (Slow Selling)
        $slowSellingProducts = Product::query()
            ->select(
                'products.name as product_name',
                'products.sku',
                'products.stock',
                DB::raw('COALESCE(SUM(CASE WHEN orders.id IS NOT NULL THEN order_items.quantity ELSE 0 END), 0) as sold_quantity'),
                DB::raw('COALESCE(SUM(CASE WHEN orders.id IS NOT NULL THEN order_items.subtotal ELSE 0 END), 0) as sold_revenue')
            )
            ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
            ->leftJoin('orders', function ($join) use ($fromDate, $toDate): void {
                $join->on('orders.id', '=', 'order_items.order_id')
                    ->where('orders.status', '!=', 'cancelled')
                    ->whereBetween('orders.created_at', [$fromDate, $toDate]);
            })
            ->groupBy('products.id', 'products.name', 'products.sku', 'products.stock')
            ->orderByRaw('sold_quantity ASC')
            ->orderByDesc('products.stock') // Nếu cùng không bán được, ưu tiên hiển thị sản phẩm tồn nhiều trước
            ->take($limit)
            ->get();

        // 4. Tính toán các thống kê chung cho báo cáo sản phẩm
        $totalSoldQuantity = (int) $bestSellingProducts->sum('sold_quantity');
        $totalSoldRevenue = (float) $bestSellingProducts->sum('sold_revenue');
        $topProduct = $bestSellingProducts->first(); // Sản phẩm quán quân bán chạy nhất
        $slowProduct = $slowSellingProducts->first(); // Sản phẩm bán chậm nhất
        $bestProductLabels = $bestSellingProducts->pluck('product_name')->values();
        $bestProductQuantities = $bestSellingProducts
            ->pluck('sold_quantity')
            ->map(fn ($value) => (int) $value)
            ->values();

        return view('admin.reports.products', compact(
            'fromDate',
            'toDate',
            'limit',
            'bestSellingProducts',
            'slowSellingProducts',
            'totalSoldQuantity',
            'totalSoldRevenue',
            'topProduct',
            'slowProduct',
            'bestProductLabels',
            'bestProductQuantities'
        ));
    }

    /**
     * Trang Dashboard Tổng quan (Bảng điều khiển chính của Admin).
     * Hiển thị nhanh các con số thống kê chính về đơn hàng, doanh thu, hàng tồn kho,
     * danh sách đơn hàng mới, sản phẩm bán chạy, nhật ký hệ thống và doanh thu 7 ngày gần nhất.
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        // 1. Thống kê số lượng thực thể
        $usersCount = User::count();
        $productsCount = Product::count();
        $ordersCount = Order::count();
        
        // Thống kê cảnh báo tồn kho
        $lowStockCount = Product::where('stock', '<=', 10)->count(); // Sắp hết hàng (tồn <= 10)
        $outOfStockCount = Product::where('stock', 0)->count(); // Đã hết hàng hoàn toàn
        
        // Tổng giá trị hàng hóa hiện tại có trong kho hàng
        $stockValue = Product::query()
            ->selectRaw('SUM(price * stock) as total_value')
            ->value('total_value') ?? 0;

        // 2. Thống kê theo trạng thái đơn hàng
        $pendingOrdersCount = Order::where('status', 'pending')->count();
        $completedOrdersCount = Order::where('status', 'completed')->count();

        // 3. Thống kê doanh thu theo thời gian
        $todayRevenue = (clone $this->payableOrderQuery())
            ->whereDate('created_at', today())
            ->sum('total');
        $monthRevenue = (clone $this->payableOrderQuery())
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->sum('total');

        // Thống kê tỷ lệ các trạng thái đơn hàng để vẽ biểu đồ tròn
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
        
        // 4. Lấy danh sách các bản ghi hiển thị nhanh (Widget)
        $lowStockProducts = Product::where('stock', '<=', 10)
            ->orderBy('stock', 'asc')
            ->take(5)
            ->get(); // Top 5 sản phẩm tồn kho ít nhất
        
        $recentOrders = Order::query()
            ->latest()
            ->take(5)
            ->get(); // 5 đơn hàng mới đặt gần đây nhất
        
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
            ->get(); // Top 5 sản phẩm bán chạy nhất mọi thời đại
        
        $recentLogs = SystemLog::latest()->take(5)->get(); // 5 dòng nhật ký hoạt động hệ thống mới nhất

        // 5. Thống kê doanh thu chi tiết trong 7 ngày gần đây nhất (Vẽ biểu đồ cột/đường)
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

    /**
     * Trang thống kê chi tiết nâng cao (phục vụ lọc khoảng thời gian tùy chọn).
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function statistics(Request $request)
    {
        // 1. Validate tham số lọc
        $filters = $request->validate([
            'from_date' => ['nullable', 'date'],
            'to_date' => ['nullable', 'date'],
            'period' => ['nullable', 'integer', 'in:7,30,90'], // Chu kỳ nhanh (7 ngày, 30 ngày, 90 ngày)
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

        // 2. Thực hiện truy vấn dữ liệu đơn hàng
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
        
        // 3. Doanh thu hàng ngày cho biểu đồ
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

        // 4. Danh sách Top 10 sản phẩm bán chạy trong giai đoạn lọc
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
        
        // 5. Thống kê tỷ trọng các phương thức thanh toán
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

    /**
     * Hiển thị danh sách và trang phân quyền (Permissions) của các vai trò trong hệ thống.
     *
     * @return \Illuminate\View\View
     */
    public function permissions()
    {
        $roles = RolePermission::all();
        return view('admin.permissions', compact('roles'));
    }

    /**
     * Hiển thị trang danh sách nhật ký hoạt động hệ thống (System Logs).
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function logs(Request $request)
    {
        $query = SystemLog::query();

        // Sắp xếp bản ghi nhật ký theo lựa chọn của người dùng (mới nhất hoặc cũ nhất)
        $sort = $request->get('sort', 'latest');
        if ($sort == 'oldest') {
            $query->oldest();
        } else {
            $query->latest();
        }

        $logs = $query->paginate(20); // Phân trang 20 dòng log trên mỗi trang

        return view('admin.logs', compact('logs', 'sort'));
    }

    /**
     * Xử lý cập nhật phân quyền hệ thống cho các vai trò và ghi nhận sự thay đổi vào logs.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePermissions(Request $request)
    {
        $permissions = $request->input('permissions', []);
        $oldData = [];
        $newData = [];

        // Sử dụng vòng lặp duyệt qua từng vai trò được cập nhật quyền từ form
        foreach ($permissions as $roleId => $data) {
            $role = RolePermission::find($roleId);
            if ($role) {
                // Lưu lại dữ liệu cũ trước khi chỉnh sửa để ghi nhật ký chi tiết
                $oldData[$role->role_name] = $role->toArray();
                
                // Cập nhật các quyền mới (Xem, Thêm, Sửa, Xóa, Phê duyệt)
                $role->update([
                    'can_view' => isset($data['can_view']) ? $data['can_view'] : 0,
                    'can_add' => isset($data['can_add']) ? $data['can_add'] : 0,
                    'can_edit' => isset($data['can_edit']) ? $data['can_edit'] : 0,
                    'can_delete' => isset($data['can_delete']) ? $data['can_delete'] : 0,
                    'can_approve' => isset($data['can_approve']) ? $data['can_approve'] : 0,
                ]);

                // Lưu lại dữ liệu mới sau khi sửa đổi
                $newData[$role->role_name] = $role->toArray();
            }
        }

        // Ghi lại hành động cập nhật phân quyền này vào nhật ký hệ thống SystemLog
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

