<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Controller BannerController
 *
 * Quản lý các Banner quảng cáo của hệ thống trong trang quản trị Admin.
 * Hỗ trợ các chức năng: Xem danh sách, thêm mới banner quảng cáo (tải lên hình ảnh),
 * thay đổi trạng thái hoạt động (bật/tắt ẩn hiện) và xóa banner (đồng thời xóa tệp tin ảnh khỏi Storage).
 */
class BannerController extends Controller
{
    /**
     * Hiển thị danh sách toàn bộ banner quảng cáo, sắp xếp theo thứ tự hiển thị.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $banners = Banner::orderBy('sort_order', 'asc')->get();
        return view('admin.banners', compact('banners'));
    }

    /**
     * Xử lý thêm mới banner quảng cáo và lưu hình ảnh tải lên.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // 1. Xác thực dữ liệu banner
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|max:2048', // Bắt buộc là ảnh và tối đa 2MB
            'link' => 'nullable|string',
            'position' => 'required|string',
        ]);

        // 2. Tải và lưu trữ ảnh banner vào thư mục banners trong Storage (public disk)
        $path = $request->file('image')->store('banners', 'public');

        // 3. Tạo bản ghi banner mới trong cơ sở dữ liệu
        Banner::create([
            'title' => $request->title,
            'image_url' => '/storage/' . $path, // Lưu đường dẫn tương đối phục vụ hiển thị
            'link' => $request->link,
            'position' => $request->position,
            'sort_order' => $request->sort_order ?? 0,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => true, // Banner mới tạo mặc định hoạt động
        ]);

        return redirect()->back()->with('success', 'Đã tải lên banner mới thành công!');
    }

    /**
     * Bật/tắt trạng thái ẩn hiển (Kích hoạt/Vô hiệu hóa) của banner quảng cáo.
     *
     * @param int $id ID của banner
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggle($id)
    {
        $banner = Banner::findOrFail($id);
        
        // Đảo ngược trạng thái hoạt động hiện tại
        $banner->update(['is_active' => !$banner->is_active]);
        
        return redirect()->back()->with('success', 'Đã đổi trạng thái ẩn/hiện của banner!');
    }

    /**
     * Xóa banner quảng cáo vĩnh viễn và dọn dẹp ảnh đi kèm trong Storage.
     *
     * @param int $id ID của banner
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $banner = Banner::findOrFail($id);
        
        // 1. Kiểm tra và xóa tệp tin hình ảnh vật lý trong Storage disk public
        if ($banner->image_url) {
            $path = str_replace('/storage/', '', $banner->image_url);
            Storage::disk('public')->delete($path);
        }
        
        // 2. Xóa bản ghi banner trong Database
        $banner->delete();
        
        return redirect()->back()->with('success', 'Đã xóa banner vĩnh viễn!');
    }
}

