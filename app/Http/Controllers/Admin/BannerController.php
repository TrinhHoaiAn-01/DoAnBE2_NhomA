<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    // Hiển thị danh sách banner quảng cáo
    public function index()
    {
        $banners = Banner::orderBy('sort_order', 'asc')->get();
        return view('admin.banners', compact('banners'));
    }

    // Thêm mới banner quảng cáo và upload ảnh
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|max:2048',
            'link' => 'nullable|string',
            'position' => 'required|string',
        ]);

        $path = $request->file('image')->store('banners', 'public');

        Banner::create([
            'title' => $request->title,
            'image_url' => '/storage/' . $path,
            'link' => $request->link,
            'position' => $request->position,
            'sort_order' => $request->sort_order ?? 0,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'Đã tải lên banner mới thành công!');
    }

    // Bật/tắt ẩn hiển banner quảng cáo
    public function toggle($id)
    {
        $banner = Banner::findOrFail($id);
        $banner->update(['is_active' => !$banner->is_active]);
        return redirect()->back()->with('success', 'Đã đổi trạng thái ẩn/hiện của banner!');
    }

    // Xóa banner quảng cáo và ảnh đi kèm
    public function destroy($id)
    {
        $banner = Banner::findOrFail($id);
        // Xóa ảnh vật lý
        if ($banner->image_url) {
            $path = str_replace('/storage/', '', $banner->image_url);
            Storage::disk('public')->delete($path);
        }
        $banner->delete();
        return redirect()->back()->with('success', 'Đã xóa banner vĩnh viễn!');
    }
}
