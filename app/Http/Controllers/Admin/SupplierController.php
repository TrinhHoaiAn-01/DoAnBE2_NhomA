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
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        $supplier = Supplier::create($data);

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

    // Cập nhật thông tin NCC
    public function update(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);
        $oldData = $supplier->toArray();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        $supplier->update($data);

        // Ghi vào Nhật ký hệ thống
        SystemLog::create([
            'user_name' => Auth::user()->name ?? 'Hệ thống',
            'action' => 'Cập nhật Nhà cung cấp',
            'target_type' => 'Nhà cung cấp',
            'old_data' => $oldData,
            'new_data' => $supplier->toArray(),
        ]);

        return redirect()->back()->with('success', 'Cập nhật nhà cung cấp thành công!');
    }
}