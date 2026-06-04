<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesCrudSafety;
use App\Http\Controllers\Controller;
use App\Models\InventoryCheck;
use App\Models\Product;
use App\Models\StockHistory;
use App\Models\Supplier;
use App\Models\SystemLog;
use App\Models\WarehouseIssue;
use App\Models\WarehouseReceipt;
use App\Models\WarehouseReceiptItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WarehouseController extends Controller
{
    use HandlesCrudSafety;

    public function receipts()
    {
        $receipts = WarehouseReceipt::with(['supplier', 'user'])->latest()->paginate(10);

        return view('admin.warehouse.receipts.index', compact('receipts'));
    }

    public function createReceipt()
    {
        $suppliers = Supplier::all();
        $products = Product::where('is_active', true)->get();

        return view('admin.warehouse.receipts.create', compact('suppliers', 'products'));
    }

    public function storeReceipt(Request $request): RedirectResponse
    {
        $data = $this->validateCrud($request, [
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'products' => ['required', 'array', 'min:1'],
            'products.*.id' => ['required', 'exists:products,id'],
            'products.*.quantity' => ['required', 'integer', 'min:1'],
            'products.*.price' => ['required', 'numeric', 'min:0'],
            'products.*.batch_code' => ['nullable', 'string', 'max:255'],
            'products.*.expires_at' => ['nullable', 'date'],
            'note' => ['nullable', 'string'],
        ]);

        return $this->runCrudOperation(function () use ($data): RedirectResponse {
            $this->transaction(function () use ($data): void {
                $totalAmount = 0;
                $items = [];

                foreach ($data['products'] as $prod) {
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

                $receipt = WarehouseReceipt::create([
                    'code' => 'PN' . now()->format('YmdHis') . random_int(10, 99),
                    'supplier_id' => $data['supplier_id'],
                    'user_id' => Auth::id(),
                    'total_amount' => $totalAmount,
                    'note' => $data['note'] ?? null,
                    'status' => 'completed',
                ]);

                foreach ($items as $item) {
                    $product = Product::query()->lockForUpdate()->findOrFail($item['product_id']);
                    $receipt->items()->create($item);
                    $product->increment('stock', $item['quantity']);

                    StockHistory::create([
                        'product_id' => $product->id,
                        'type' => 'in',
                        'quantity' => $item['quantity'],
                        'reference_type' => 'receipt',
                        'reference_code' => $receipt->code,
                        'note' => 'Nhập kho theo phiếu ' . $receipt->code,
                    ]);
                }

                SystemLog::create([
                    'user_name' => Auth::user()->name ?? 'Hệ thống',
                    'action' => 'Tạo phiếu nhập kho',
                    'target_type' => 'Nhập kho',
                    'old_data' => [],
                    'new_data' => ['receipt_code' => $receipt->code, 'total' => $totalAmount],
                ]);
            });

            return redirect()->route('admin.warehouse.receipts')
                ->with('success', 'Tạo phiếu nhập kho thành công. Tồn kho đã được khóa khi cập nhật để tránh ghi lệch dữ liệu.');
        }, 'tạo phiếu nhập kho');
    }

    public function showReceipt($id)
    {
        $receipt = WarehouseReceipt::with(['supplier', 'user', 'items.product'])->findOrFail($id);

        return view('admin.warehouse.receipts.show', compact('receipt'));
    }

    public function inventory()
    {
        $products = Product::orderBy('stock', 'asc')->paginate(15);
        $lowStockCount = Product::where('stock', '<=', 10)->count();
        $outOfStockCount = Product::where('stock', 0)->count();
        $totalStock = Product::sum('stock');
        $expiringBatches = WarehouseReceiptItem::with(['product', 'receipt'])
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', now()->addDays(30))
            ->orderBy('expires_at', 'asc')
            ->get();

        return view('admin.warehouse.inventory.index', compact('products', 'lowStockCount', 'outOfStockCount', 'totalStock', 'expiringBatches'));
    }

    public function stockHistory($id)
    {
        $product = Product::with('stockHistories')->findOrFail($id);

        return view('admin.warehouse.inventory.history', compact('product'));
    }

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

    public function storeIssue(Request $request): RedirectResponse
    {
        $data = $this->validateCrud($request, [
            'reason' => ['required', 'string'],
            'products' => ['required', 'array', 'min:1'],
            'products.*.id' => ['required', 'exists:products,id'],
            'products.*.quantity' => ['required', 'integer', 'min:1'],
            'note' => ['nullable', 'string'],
        ]);

        return $this->runCrudOperation(function () use ($data): RedirectResponse {
            $this->transaction(function () use ($data): void {
                $productsById = [];

                foreach ($data['products'] as $prod) {
                    $product = Product::query()->lockForUpdate()->findOrFail($prod['id']);
                    $productsById[$product->id] = $product;

                    if ($product->stock < $prod['quantity']) {
                        throw \Illuminate\Validation\ValidationException::withMessages([
                            'stock' => "Sản phẩm {$product->name} không đủ tồn kho để xuất. Hiện còn {$product->stock}.",
                        ]);
                    }
                }

                $issue = WarehouseIssue::create([
                    'code' => 'PX' . now()->format('YmdHis') . random_int(10, 99),
                    'user_id' => Auth::id(),
                    'reason' => $data['reason'],
                    'note' => $data['note'] ?? null,
                    'status' => 'completed',
                ]);

                foreach ($data['products'] as $prod) {
                    $product = $productsById[$prod['id']];
                    $issue->items()->create([
                        'product_id' => $prod['id'],
                        'quantity' => $prod['quantity'],
                    ]);

                    $product->decrement('stock', $prod['quantity']);

                    StockHistory::create([
                        'product_id' => $product->id,
                        'type' => 'out',
                        'quantity' => $prod['quantity'],
                        'reference_type' => 'issue',
                        'reference_code' => $issue->code,
                        'note' => 'Xuất kho: ' . $data['reason'],
                    ]);
                }

                SystemLog::create([
                    'user_name' => Auth::user()->name ?? 'Hệ thống',
                    'action' => 'Tạo phiếu xuất kho',
                    'target_type' => 'Xuất kho',
                    'old_data' => [],
                    'new_data' => ['issue_code' => $issue->code, 'reason' => $data['reason']],
                ]);
            });

            return redirect()->route('admin.warehouse.issues')
                ->with('success', 'Tạo phiếu xuất kho thành công. Tồn kho đã được kiểm tra và khóa trước khi trừ.');
        }, 'tạo phiếu xuất kho');
    }

    public function showIssue($id)
    {
        $issue = WarehouseIssue::with(['user', 'items.product'])->findOrFail($id);

        return view('admin.warehouse.issues.show', compact('issue'));
    }

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

    public function storeCheck(Request $request): RedirectResponse
    {
        $data = $this->validateCrud($request, [
            'note' => ['nullable', 'string'],
            'products' => ['required', 'array', 'min:1'],
            'products.*.id' => ['required', 'exists:products,id'],
            'products.*.actual_stock' => ['required', 'integer', 'min:0'],
        ]);

        return $this->runCrudOperation(function () use ($data): RedirectResponse {
            $this->transaction(function () use ($data): void {
                $check = InventoryCheck::create([
                    'code' => 'KK' . now()->format('YmdHis') . random_int(10, 99),
                    'user_id' => Auth::id(),
                    'note' => $data['note'] ?? null,
                    'status' => 'completed',
                ]);

                foreach ($data['products'] as $prod) {
                    $product = Product::query()->lockForUpdate()->findOrFail($prod['id']);
                    $oldStock = $product->stock;
                    $actualStock = $prod['actual_stock'];
                    $difference = $actualStock - $oldStock;

                    if ($difference === 0) {
                        continue;
                    }

                    $check->items()->create([
                        'product_id' => $product->id,
                        'old_stock' => $oldStock,
                        'actual_stock' => $actualStock,
                        'difference' => $difference,
                    ]);

                    $product->update(['stock' => $actualStock]);

                    StockHistory::create([
                        'product_id' => $product->id,
                        'type' => $difference > 0 ? 'in' : 'out',
                        'quantity' => abs($difference),
                        'reference_type' => 'check',
                        'reference_code' => $check->code,
                        'note' => 'Cân bằng kiểm kê: ' . ($difference > 0 ? '+' : '') . $difference,
                    ]);
                }

                if ($check->items()->count() === 0) {
                    $check->update([
                        'note' => trim(($check->note ? $check->note . ' - ' : '') . 'Kho khớp hoàn toàn.'),
                    ]);
                }

                SystemLog::create([
                    'user_name' => Auth::user()->name ?? 'Hệ thống',
                    'action' => 'Tạo phiếu kiểm kê',
                    'target_type' => 'Kiểm kê',
                    'old_data' => [],
                    'new_data' => ['check_code' => $check->code],
                ]);
            });

            return redirect()->route('admin.warehouse.checks')
                ->with('success', 'Kiểm kê kho thành công. Tồn kho đã được khóa khi cân bằng để tránh mất cập nhật.');
        }, 'kiểm kê kho');
    }

    public function showCheck($id)
    {
        $check = InventoryCheck::with(['user', 'items.product'])->findOrFail($id);

        return view('admin.warehouse.checks.show', compact('check'));
    }
}
