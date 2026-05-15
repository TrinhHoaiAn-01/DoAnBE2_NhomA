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

class WarehouseController extends Controller
{
    // Danh sách phiếu nhập kho
    public function receipts()
    {
        $receipts = WarehouseReceipt::with(['supplier', 'user'])->latest()->paginate(10);
        return view('admin.warehouse.receipts.index', compact('receipts'));
    }

    // Form thêm phiếu nhập
    public function createReceipt()
    {
        $suppliers = Supplier::all();
        $products = Product::where('is_active', true)->get();
        return view('admin.warehouse.receipts.create', compact('suppliers', 'products'));
    }

    // Lưu phiếu nhập
    public function storeReceipt(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price' => 'required|numeric|min:0',
            'note' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $totalAmount = 0;
            $items = [];
            foreach ($request->products as $prod) {
                $subtotal = $prod['quantity'] * $prod['price'];
                $totalAmount += $subtotal;
                $items[] = [
                    'product_id' => $prod['id'],
                    'quantity' => $prod['quantity'],
                    'price' => $prod['price'],
                    'subtotal' => $subtotal,
                ];
            }

            // Tạo phiếu nhập
            $receipt = WarehouseReceipt::create([
                'code' => 'PN' . date('YmdHis') . rand(10, 99),
                'supplier_id' => $request->supplier_id,
                'user_id' => Auth::id(),
                'total_amount' => $totalAmount,
                'note' => $request->note,
                'status' => 'completed',
            ]);

            // Thêm chi tiết và cộng dồn tồn kho
            foreach ($items as $item) {
                $receipt->items()->create($item);

                // Cập nhật tồn kho sản phẩm
                $product = Product::find($item['product_id']);
                $product->increment('stock', $item['quantity']);

                // Ghi nhận thẻ kho
                StockHistory::create([
                    'product_id' => $product->id,
                    'type' => 'in',
                    'quantity' => $item['quantity'],
                    'reference_type' => 'receipt',
                    'reference_code' => $receipt->code,
                    'note' => 'Nhập kho theo phiếu ' . $receipt->code,
                ]);
            }

            // Ghi Log hệ thống
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

    // Xem chi tiết phiếu nhập
    public function showReceipt($id)
    {
        $receipt = WarehouseReceipt::with(['supplier', 'user', 'items.product'])->findOrFail($id);
        return view('admin.warehouse.receipts.show', compact('receipt'));
    }
    
    // Tồn kho & Lô hàng
    public function inventory()
    {
        $products = Product::orderBy('stock', 'asc')->paginate(15);
        
        $lowStockCount = Product::where('stock', '<=', 10)->count();
        $outOfStockCount = Product::where('stock', 0)->count();
        $totalStock = Product::sum('stock');
        
        return view('admin.warehouse.inventory.index', compact('products', 'lowStockCount', 'outOfStockCount', 'totalStock'));
    }

    // Xem lịch sử thẻ kho của 1 sản phẩm
    public function stockHistory($id)
    {
        $product = Product::with('stockHistories')->findOrFail($id);
        return view('admin.warehouse.inventory.history', compact('product'));
    }

    // ==========================================
    // MODULE PHIẾU XUẤT KHO (ISSUES)
    // ==========================================

    public function issues()
    {
        $issues = WarehouseIssue::with('user')->latest()->paginate(10);
        return view('admin.warehouse.issues.index', compact('issues'));
    }

    public function createIssue()
    {
        $products = Product::where('is_active', true)->where('stock', '>', 0)->get();
        return view('admin.warehouse.issues.create', compact('products'));
    }

    public function storeIssue(Request $request)
    {
        $request->validate([
            'reason' => 'required|string',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'note' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Kiểm tra tồn kho trước khi xuất
            foreach ($request->products as $prod) {
                $product = Product::find($prod['id']);
                if ($product->stock < $prod['quantity']) {
                    throw new \Exception("Sản phẩm {$product->name} không đủ tồn kho (Còn: {$product->stock}).");
                }
            }

            // Tạo phiếu xuất
            $issue = WarehouseIssue::create([
                'code' => 'PX' . date('YmdHis') . rand(10, 99),
                'user_id' => Auth::id(),
                'reason' => $request->reason,
                'note' => $request->note,
                'status' => 'completed',
            ]);

            // Thêm chi tiết và trừ tồn kho
            foreach ($request->products as $prod) {
                $issue->items()->create([
                    'product_id' => $prod['id'],
                    'quantity' => $prod['quantity'],
                ]);

                $product = Product::find($prod['id']);
                $product->decrement('stock', $prod['quantity']);

                // Ghi nhận thẻ kho
                StockHistory::create([
                    'product_id' => $product->id,
                    'type' => 'out',
                    'quantity' => $prod['quantity'],
                    'reference_type' => 'issue',
                    'reference_code' => $issue->code,
                    'note' => 'Xuất kho: ' . $request->reason,
                ]);
            }

            // Ghi Log hệ thống
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

    public function showIssue($id)
    {
        $issue = WarehouseIssue::with(['user', 'items.product'])->findOrFail($id);
        return view('admin.warehouse.issues.show', compact('issue'));
    }

    // ==========================================
    // MODULE KIỂM KÊ KHO (INVENTORY CHECKS)
    // ==========================================

    public function checks()
    {
        $checks = InventoryCheck::with('user')->latest()->paginate(10);
        return view('admin.warehouse.checks.index', compact('checks'));
    }

    public function createCheck()
    {
        $products = Product::where('is_active', true)->get();
        return view('admin.warehouse.checks.create', compact('products'));
    }

    public function storeCheck(Request $request)
    {
        $request->validate([
            'note' => 'nullable|string',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.actual_stock' => 'required|integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            $check = InventoryCheck::create([
                'code' => 'KK' . date('YmdHis') . rand(10, 99),
                'user_id' => Auth::id(),
                'note' => $request->note,
                'status' => 'completed',
            ]);

            foreach ($request->products as $prod) {
                $product = Product::find($prod['id']);
                $oldStock = $product->stock;
                $actualStock = $prod['actual_stock'];
                $difference = $actualStock - $oldStock;

                if ($difference != 0) {
                    $check->items()->create([
                        'product_id' => $product->id,
                        'old_stock' => $oldStock,
                        'actual_stock' => $actualStock,
                        'difference' => $difference,
                    ]);

                    // Cập nhật lại tồn kho
                    $product->update(['stock' => $actualStock]);

                    // Ghi nhận thẻ kho
                    StockHistory::create([
                        'product_id' => $product->id,
                        'type' => $difference > 0 ? 'in' : 'out',
                        'quantity' => abs($difference),
                        'reference_type' => 'check',
                        'reference_code' => $check->code,
                        'note' => 'Cân bằng kiểm kê: ' . ($difference > 0 ? '+' : '') . $difference,
                    ]);
                }
            }

            // Nếu không có sản phẩm nào lệch
            if ($check->items()->count() == 0) {
                $check->note = ($check->note ? $check->note . ' - ' : '') . 'Kho khớp hoàn toàn.';
                $check->save();
            }

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

    public function showCheck($id)
    {
        $check = InventoryCheck::with(['user', 'items.product'])->findOrFail($id);
        return view('admin.warehouse.checks.show', compact('check'));
    }
}
