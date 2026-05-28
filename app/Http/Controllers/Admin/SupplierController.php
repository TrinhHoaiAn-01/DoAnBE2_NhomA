<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SystemLog;

/**
 * Controller SupplierController
 *
 * Quản lý danh sách các Nhà cung cấp (Supplier) hàng hóa của hệ thống.
 * Hỗ trợ các tính năng quản trị: Xem danh sách, thêm mới, cập nhật và xóa nhà cung cấp.
 * Mọi thay đổi về dữ liệu (Thêm, Sửa, Xóa) đều được ghi chép chi tiết vào bảng Nhật ký hệ thống (SystemLog).
 */
class SupplierController extends Controller
{
    /**
     * Hiển thị danh sách toàn bộ các nhà cung cấp, bản ghi mới nhất xếp lên đầu.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $suppliers = Supplier::latest()->get();

        return view('admin.suppliers', compact('suppliers'));
    }

    /**
     * Xử lý thêm mới một Nhà cung cấp.
     * Ghi nhận dữ liệu cũ/mới vào SystemLog.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // 1. Xác thực dữ liệu
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        // 2. Tạo bản ghi mới trong Database
        $supplier = Supplier::create($data);

        // 3. Ghi chép hành động vào Nhật ký hệ thống (System Log)
        SystemLog::create([
            'user_name' => Auth::user()->name ?? 'Hệ thống',
            'action' => 'Thêm Nhà cung cấp mới',
            'target_type' => 'Nhà cung cấp',
            'old_data' => [], // Không có dữ liệu cũ
            'new_data' => $supplier->toArray(), // Lưu lại mảng dữ liệu mới tạo
        ]);

        return redirect()->back()->with('success', 'Thêm nhà cung cấp thành công!');
    }

    /**
     * Xóa vĩnh viễn một Nhà cung cấp khỏi Database.
     * Ghi nhận dữ liệu đã xóa vào SystemLog.
     *
     * @param int $id ID của nhà cung cấp cần xóa
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);

        // Lưu lại bản sao dữ liệu trước khi xóa để làm log đối chứng
        $oldData = $supplier->toArray();

        // Tiến hành xóa
        $supplier->delete();

        // Ghi chép hành động xóa vào Nhật ký hệ thống (System Log)
        SystemLog::create([
            'user_name' => Auth::user()->name ?? 'Hệ thống',
            'action' => 'Xóa Nhà cung cấp',
            'target_type' => 'Nhà cung cấp',
            'old_data' => $oldData,
            'new_data' => [], // Sau khi xóa không còn dữ liệu mới
        ]);

        return redirect()->back()->with('success', 'Đã xóa nhà cung cấp.');
    }

    /**
     * Cập nhật thông tin chi tiết của một Nhà cung cấp.
     * Ghi nhận lại đầy đủ trạng thái dữ liệu cũ và dữ liệu mới vào SystemLog.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id ID của nhà cung cấp cần cập nhật
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);
        
        // Lưu dữ liệu cũ trước khi cập nhật
        $oldData = $supplier->toArray();

        // 1. Xác thực dữ liệu cập nhật
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        // 2. Thực hiện cập nhật trong Database
        $supplier->update($data);

        // 3. Ghi chép hành động cập nhật kèm lịch sử dữ liệu vào Nhật ký hệ thống
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