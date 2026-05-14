<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\WarehouseReceipt;
use App\Models\SystemLog;
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
}
