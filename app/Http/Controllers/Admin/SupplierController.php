<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;

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

        Supplier::create($request->all());

        return redirect()->back()->with('success', 'Thêm nhà cung cấp thành công!');
    }

    // Xử lý xóa
    public function destroy($id)
    {
        Supplier::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Đã xóa nhà cung cấp.');
    }
}
