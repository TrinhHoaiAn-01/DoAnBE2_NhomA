<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesCrudSafety;
use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Models\SystemLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SupplierController extends Controller
{
    use HandlesCrudSafety;

    public function index()
    {
        $suppliers = Supplier::latest()->get();

        return view('admin.suppliers', compact('suppliers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedSupplier($request);

        return $this->runCrudOperation(function () use ($data): RedirectResponse {
            $supplier = Supplier::create($data);

            SystemLog::create([
                'user_name' => Auth::user()->name ?? 'Hệ thống',
                'action' => 'Thêm Nhà cung cấp mới',
                'target_type' => 'Nhà cung cấp',
                'old_data' => [],
                'new_data' => $supplier->toArray(),
            ]);

            return redirect()->back()
                ->with('success', 'Đã thêm nhà cung cấp. Hệ thống đã kiểm tra tên để tránh bản ghi trùng.');
        }, 'thêm nhà cung cấp');
    }

    public function destroy($id): RedirectResponse
    {
        $supplier = Supplier::find($id);

        if (! $supplier) {
            return redirect()->back()
                ->with('error', 'Nhà cung cấp này đã được xóa trước đó. Danh sách đã được làm mới để tránh xóa nhầm.');
        }

        return $this->runCrudOperation(function () use ($supplier): RedirectResponse {
            $oldData = [];

            $this->transaction(function () use ($supplier, &$oldData): void {
                $lockedSupplier = $this->lockForCrud($supplier);
                $oldData = $lockedSupplier->toArray();
                $lockedSupplier->delete();
            });

            SystemLog::create([
                'user_name' => Auth::user()->name ?? 'Hệ thống',
                'action' => 'Xóa Nhà cung cấp',
                'target_type' => 'Nhà cung cấp',
                'old_data' => $oldData,
                'new_data' => [],
            ]);

            return redirect()->back()
                ->with('success', 'Đã xóa nhà cung cấp. Hệ thống đã kiểm tra xung đột trong lúc xóa.');
        }, 'xóa nhà cung cấp');
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $supplier = Supplier::find($id);

        if (! $supplier) {
            return redirect()->back()
                ->with('error', 'Nhà cung cấp không còn tồn tại. Vui lòng tải lại trang trước khi sửa.');
        }

        $data = $this->validatedSupplier($request, $supplier);

        return $this->runCrudOperation(function () use ($request, $supplier, $data): RedirectResponse {
            $oldData = [];
            $newData = [];

            $this->transaction(function () use ($request, $supplier, $data, &$oldData, &$newData): void {
                $lockedSupplier = $this->lockForCrud($supplier);
                $this->assertFreshRecord($request, $lockedSupplier, 'nhà cung cấp');

                $oldData = $lockedSupplier->toArray();
                $lockedSupplier->update($data);
                $newData = $lockedSupplier->fresh()->toArray();
            });

            SystemLog::create([
                'user_name' => Auth::user()->name ?? 'Hệ thống',
                'action' => 'Cập nhật Nhà cung cấp',
                'target_type' => 'Nhà cung cấp',
                'old_data' => $oldData,
                'new_data' => $newData,
            ]);

            return redirect()->back()
                ->with('success', 'Đã cập nhật nhà cung cấp. Hệ thống đã kiểm tra phiên chỉnh sửa trước khi lưu.');
        }, 'cập nhật nhà cung cấp');
    }

    private function validatedSupplier(Request $request, ?Supplier $supplier = null): array
    {
        return $this->validateCrud($request, [
            'name' => ['required', 'string', 'max:255', Rule::unique('suppliers', 'name')->ignore($supplier)],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
        ], [
            'name' => 'tên nhà cung cấp',
            'phone' => 'số điện thoại nhà cung cấp',
            'address' => 'địa chỉ nhà cung cấp',
        ]);
    }
}
