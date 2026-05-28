<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\WarehouseReceipt;
use App\Models\SystemLog;
use App\Models\StockHistory;
use App\Models\WarehouseIssue;
use App\Models\InventoryCheck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Controller WarehouseController (Admin)
 *
 * Quản lý toàn bộ quy trình nghiệp vụ kho hàng bao gồm:
 * 1. Phiếu nhập kho (Warehouse Receipts) từ nhà cung cấp (cộng dồn tồn kho, ghi nhận thẻ kho).
 * 2. Phiếu xuất kho hủy/hỏng/thử nghiệm (Warehouse Issues - trừ tồn kho, ghi nhận thẻ kho).
 * 3. Kiểm kê kho định kỳ (Inventory Checks - cân bằng tồn kho tự động, xử lý chênh lệch dư/thiếu).
 * 4. Xem tồn kho tổng thể, thẻ kho lịch sử chi tiết sản phẩm và các lô hàng sắp hết hạn sử dụng.
 */
class WarehouseController extends Controller
{
    /**
     * Hiển thị danh sách các phiếu nhập kho (Phân trang 10 phiếu/trang).
     *
     * @return \Illuminate\View\View
     */
    public function receipts()
    {
        $receipts = WarehouseReceipt::with(['supplier', 'user'])->latest()->paginate(10);
        return view('admin.warehouse.receipts.index', compact('receipts'));
    }

    /**
     * Hiển thị form tạo phiếu nhập kho.
     * Cung cấp danh sách nhà cung cấp và các sản phẩm đang được kích hoạt bán.
     *
     * @return \Illuminate\View\View
     */
    public function createReceipt()
    {
        $suppliers = Supplier::all();
        $products = Product::where('is_active', true)->get();
        return view('admin.warehouse.receipts.create', compact('suppliers', 'products'));
    }

    /**
     * Xử lý lưu phiếu nhập kho và tăng số lượng tồn kho sản phẩm tương ứng.
     * Áp dụng DB Transaction để đảm bảo tính toàn vẹn dữ liệu.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeReceipt(Request $request)
    {
        // 1. Xác thực dữ liệu đầu vào của phiếu nhập
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price' => 'required|numeric|min:0',
            'products.*.batch_code' => 'nullable|string',
            'products.*.expires_at' => 'nullable|date',
            'note' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $totalAmount = 0;
            $items = [];
            
            // Tính toán chi tiết tổng tiền nhập hàng của từng sản phẩm
            foreach ($request->products as $prod) {
                $subtotal = $prod['quantity'] * $prod['price'];
                $totalAmount += $subtotal;
                $items[] = [
                    'product_id' => $prod['id'],
                    'quantity' => $prod['quantity'],
                    'price' => $prod['price'],
                    'subtotal' => $subtotal,
                    'batch_code' => $prod['batch_code'] ?? null,
                    'expires_at' => $prod['expires_at'] ?? null,
                ];
            }

            // 2. Tạo phiếu nhập chính (Warehouse Receipt)
            $receipt = WarehouseReceipt::create([
                'code' => 'PN' . date('YmdHis') . rand(10, 99), // Tạo mã phiếu nhập duy nhất
                'supplier_id' => $request->supplier_id,
                'user_id' => Auth::id(),
                'total_amount' => $totalAmount,
                'note' => $request->note,
                'status' => 'completed',
            ]);

            // 3. Thêm chi tiết phiếu nhập, cộng dồn tồn kho sản phẩm, ghi nhận Thẻ kho (StockHistory)
            foreach ($items as $item) {
                $receipt->items()->create($item);

                // Cập nhật tăng số lượng tồn kho của sản phẩm trong Database
                $product = Product::find($item['product_id']);
                $product->increment('stock', $item['quantity']);

                // Ghi nhận biến động thẻ kho (Stock History) dạng nhập kho (in)
                StockHistory::create([
                    'product_id' => $product->id,
                    'type' => 'in',
                    'quantity' => $item['quantity'],
                    'reference_type' => 'receipt',
                    'reference_code' => $receipt->code,
                    'note' => 'Nhập kho theo phiếu ' . $receipt->code,
                ]);
            }

            // 4. Ghi nhận hành động vào Nhật ký hệ thống (System Log)
            SystemLog::create([
                'user_name' => Auth::user()->name ?? 'Hệ thống',
                'action' => 'Tạo phiếu nhập kho',
                'target_type' => 'Nhập kho',
                'old_data' => [],
                'new_data' => ['receipt_code' => $receipt->code, 'total' => $totalAmount],
            ]);

            DB::commit();
            return redirect()->route('admin.warehouse.receipts')->with('success', 'Tạo phiếu nhập kho thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Hiển thị thông tin chi tiết một phiếu nhập kho cụ thể.
     *
     * @param int $id ID của phiếu nhập kho
     * @return \Illuminate\View\View
     */
    public function showReceipt($id)
    {
        $receipt = WarehouseReceipt::with(['supplier', 'user', 'items.product'])->findOrFail($id);
        return view('admin.warehouse.receipts.show', compact('receipt'));
    }
    
    /**
     * Trang xem danh sách tồn kho tổng thể của các sản phẩm.
     * Hiển thị thống kê sắp hết hàng, đã hết hàng, tổng tồn và cảnh báo các lô hàng sắp hết hạn (trong 30 ngày).
     *
     * @return \Illuminate\View\View
     */
    public function inventory()
    {
        // Phân trang danh sách sản phẩm, hiển thị sản phẩm có lượng tồn kho ít nhất lên đầu
        $products = Product::orderBy('stock', 'asc')->paginate(15);
        
        // Thống kê nhanh lượng tồn kho
        $lowStockCount = Product::where('stock', '<=', 10)->count();
        $outOfStockCount = Product::where('stock', 0)->count();
        $totalStock = Product::sum('stock');
        
        // Truy vấn các lô sản phẩm nhập kho sắp hết hạn sử dụng trong vòng 30 ngày tới
        $expiringBatches = \App\Models\WarehouseReceiptItem::with(['product', 'receipt'])
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', now()->addDays(30))
            ->orderBy('expires_at', 'asc')
            ->get();
        
        return view('admin.warehouse.inventory.index', compact('products', 'lowStockCount', 'outOfStockCount', 'totalStock', 'expiringBatches'));
    }

    /**
     * Hiển thị lịch sử thẻ kho (Stock History) biến động xuất nhập của một sản phẩm cụ thể.
     *
     * @param int $id ID của sản phẩm
     * @return \Illuminate\View\View
     */
    public function stockHistory($id)
    {
        $product = Product::with('stockHistories')->findOrFail($id);
        return view('admin.warehouse.inventory.history', compact('product'));
    }

    // ==========================================
    // MODULE PHIẾU XUẤT KHO (ISSUES)
    // ==========================================

    /**
     * Hiển thị danh sách các phiếu xuất kho (Phân trang 10 phiếu/trang).
     *
     * @return \Illuminate\View\View
     */
    public function issues()
    {
        $issues = WarehouseIssue::with('user')->latest()->paginate(10);
        return view('admin.warehouse.issues.index', compact('issues'));
    }

    /**
     * Hiển thị form tạo phiếu xuất kho.
     * Chỉ cung cấp các sản phẩm đang hoạt động và còn hàng trong kho để xuất.
     *
     * @return \Illuminate\View\View
     */
    public function createIssue()
    {
        $products = Product::where('is_active', true)->where('stock', '>', 0)->get();
        return view('admin.warehouse.issues.create', compact('products'));
    }

    /**
     * Xử lý lưu phiếu xuất kho (hủy/hỏng/thử nghiệm) và trừ bớt tồn kho sản phẩm.
     * Có ràng buộc kiểm tra lượng tồn thực tế phía Backend để tránh xuất quá số lượng hiện có.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeIssue(Request $request)
    {
        // 1. Xác thực thông tin phiếu xuất
        $request->validate([
            'reason' => 'required|string', // Lý do xuất (vd: Hàng lỗi, Hết hạn, Hủy mẫu)
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'note' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Ràng buộc nghiêm ngặt: Kiểm tra xem tất cả các sản phẩm có đủ lượng tồn kho để xuất không
            foreach ($request->products as $prod) {
                $product = Product::find($prod['id']);
                if ($product->stock < $prod['quantity']) {
                    throw new \Exception("Sản phẩm {$product->name} không đủ tồn kho để xuất (Trong kho còn: {$product->stock}).");
                }
            }

            // 2. Tạo phiếu xuất chính (Warehouse Issue)
            $issue = WarehouseIssue::create([
                'code' => 'PX' . date('YmdHis') . rand(10, 99),
                'user_id' => Auth::id(),
                'reason' => $request->reason,
                'note' => $request->note,
                'status' => 'completed',
            ]);

            // 3. Thêm chi tiết xuất, trừ tồn kho và ghi nhận Thẻ kho (StockHistory) dạng xuất kho (out)
            foreach ($request->products as $prod) {
                $issue->items()->create([
                    'product_id' => $prod['id'],
                    'quantity' => $prod['quantity'],
                ]);

                // Trừ bớt tồn kho sản phẩm
                $product = Product::find($prod['id']);
                $product->decrement('stock', $prod['quantity']);

                // Ghi nhận biến động thẻ kho dạng xuất (out)
                StockHistory::create([
                    'product_id' => $product->id,
                    'type' => 'out',
                    'quantity' => $prod['quantity'],
                    'reference_type' => 'issue',
                    'reference_code' => $issue->code,
                    'note' => 'Xuất kho: ' . $request->reason,
                ]);
            }

            // 4. Ghi nhận hành động vào Nhật ký hệ thống (System Log)
            SystemLog::create([
                'user_name' => Auth::user()->name ?? 'Hệ thống',
                'action' => 'Tạo phiếu xuất kho',
                'target_type' => 'Xuất kho',
                'old_data' => [],
                'new_data' => ['issue_code' => $issue->code, 'reason' => $request->reason],
            ]);

            DB::commit();
            return redirect()->route('admin.warehouse.issues')->with('success', 'Tạo phiếu xuất kho thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    /**
     * Hiển thị thông tin chi tiết một phiếu xuất kho cụ thể.
     *
     * @param int $id ID của phiếu xuất kho
     * @return \Illuminate\View\View
     */
    public function showIssue($id)
    {
        $issue = WarehouseIssue::with(['user', 'items.product'])->findOrFail($id);
        return view('admin.warehouse.issues.show', compact('issue'));
    }

    // ==========================================
    // MODULE KIỂM KÊ KHO (INVENTORY CHECKS)
    // ==========================================

    /**
     * Hiển thị danh sách các phiếu kiểm kê kho (Phân trang 10 phiếu/trang).
     *
     * @return \Illuminate\View\View
     */
    public function checks()
    {
        $checks = InventoryCheck::with('user')->latest()->paginate(10);
        return view('admin.warehouse.checks.index', compact('checks'));
    }

    /**
     * Hiển thị form tạo phiếu kiểm kê kho.
     *
     * @return \Illuminate\View\View
     */
    public function createCheck()
    {
        $products = Product::where('is_active', true)->get();
        return view('admin.warehouse.checks.create', compact('products'));
    }

    /**
     * Xử lý lưu phiếu kiểm kê kho và tự động cân bằng số lượng tồn kho sản phẩm.
     * Tính toán chênh lệch chênh lệch (thừa/thiếu) so với tồn hệ thống, 
     * tự động cộng/trừ tồn kho và ghi thẻ kho với chênh lệch chênh lệch tương ứng.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeCheck(Request $request)
    {
        // 1. Xác thực thông tin phiếu kiểm kê
        $request->validate([
            'note' => 'nullable|string',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.actual_stock' => 'required|integer|min:0', // Số lượng kiểm thực tế
        ]);

        try {
            DB::beginTransaction();

            // 2. Tạo phiếu kiểm kê kho
            $check = InventoryCheck::create([
                'code' => 'KK' . date('YmdHis') . rand(10, 99),
                'user_id' => Auth::id(),
                'note' => $request->note,
                'status' => 'completed',
            ]);

            // 3. Duyệt danh sách kiểm kê và xử lý lệch tồn kho
            foreach ($request->products as $prod) {
                $product = Product::find($prod['id']);
                $oldStock = $product->stock; // Số lượng tồn trên hệ thống trước kiểm kê
                $actualStock = $prod['actual_stock']; // Số lượng thực tế kiểm đếm được
                $difference = $actualStock - $oldStock; // Chênh lệch (dương là thừa, âm là thiếu)

                // Nếu có sự chênh lệch (lệch tồn kho) thì tiến hành cân bằng
                if ($difference != 0) {
                    $check->items()->create([
                        'product_id' => $product->id,
                        'old_stock' => $oldStock,
                        'actual_stock' => $actualStock,
                        'difference' => $difference,
                    ]);

                    // Cập nhật lại số lượng tồn kho thực tế vào CSDL
                    $product->update(['stock' => $actualStock]);

                    // Ghi nhận biến động chênh lệch vào thẻ kho (StockHistory)
                    StockHistory::create([
                        'product_id' => $product->id,
                        'type' => $difference > 0 ? 'in' : 'out', // Lệch thừa ghi nhập (in), lệch thiếu ghi xuất (out)
                        'quantity' => abs($difference),
                        'reference_type' => 'check',
                        'reference_code' => $check->code,
                        'note' => 'Cân bằng kiểm kê: ' . ($difference > 0 ? '+' : '') . $difference,
                    ]);
                }
            }

            // Nếu không phát hiện bất kỳ sản phẩm nào lệch tồn kho
            if ($check->items()->count() == 0) {
                $check->note = ($check->note ? $check->note . ' - ' : '') . 'Kho khớp hoàn toàn.';
                $check->save();
            }

            // 4. Ghi nhận hành động vào Nhật ký hệ thống (System Log)
            SystemLog::create([
                'user_name' => Auth::user()->name ?? 'Hệ thống',
                'action' => 'Tạo phiếu kiểm kê',
                'target_type' => 'Kiểm kê',
                'old_data' => [],
                'new_data' => ['check_code' => $check->code],
            ]);

            DB::commit();
            return redirect()->route('admin.warehouse.checks')->with('success', 'Kiểm kê kho thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Hiển thị chi tiết thông tin một phiếu kiểm kê kho và các chênh lệch chênh lệch.
     *
     * @param int $id ID của phiếu kiểm kê kho
     * @return \Illuminate\View\View
     */
    public function showCheck($id)
    {
        $check = InventoryCheck::with(['user', 'items.product'])->findOrFail($id);
        return view('admin.warehouse.checks.show', compact('check'));
    }
}

