<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SystemLog;

class SupplierController extends Controller
{
    // Hiển thị danh sách NCC
    public function index()
    {
        $suppliers = Supplier::latest()->get();
        return view('admin.suppliers.index', compact('suppliers'));
    }

    // Lưu NCC mới
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $supplier = Supplier::create($request->all());

        // Ghi vào Nhật ký hệ thống
        SystemLog::create([
            'user_name' => Auth::user()->name ?? 'Hệ thống',
            'action' => 'Thêm Nhà cung cấp mới',
            'target_type' => 'Nhà cung cấp',
            'old_data' => [],
            'new_data' => $supplier->toArray(),
        ]);

        return redirect()->back()->with('success', 'Thêm nhà cung cấp thành công!');
    }

    // Xử lý xóa
    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);
        $oldData = $supplier->toArray();
        $supplier->delete();

        // Ghi vào Nhật ký hệ thống
        SystemLog::create([
            'user_name' => Auth::user()->name ?? 'Hệ thống',
            'action' => 'Xóa Nhà cung cấp',
            'target_type' => 'Nhà cung cấp',
            'old_data' => $oldData,
            'new_data' => [],
        ]);

        return redirect()->back()->with('success', 'Đã xóa nhà cung cấp.');
    }
}
