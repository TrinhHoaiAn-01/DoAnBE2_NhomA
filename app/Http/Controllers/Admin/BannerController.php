<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesCrudSafety;
use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    use HandlesCrudSafety;

    public function index()
    {
        $banners = Banner::orderBy('sort_order', 'asc')->get();

        return view('admin.banners', compact('banners'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateCrud($request, [
            'title' => ['required', 'string', 'max:255'],
            'image' => ['required', 'image', 'max:2048'],
            'link' => ['nullable', 'url', 'max:2048'],
            'position' => ['required', 'string', 'max:50'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ], [
            'title' => 'tiêu đề banner',
            'image' => 'hình ảnh banner',
        ]);

        return $this->runCrudOperation(function () use ($request, $data): RedirectResponse {
            $path = $request->file('image')->store('banners', 'public');

            Banner::create([
                'title' => $data['title'],
                'image_url' => '/storage/' . $path,
                'link' => $data['link'] ?? null,
                'position' => $data['position'],
                'sort_order' => $data['sort_order'] ?? 0,
                'start_date' => $data['start_date'] ?? null,
                'end_date' => $data['end_date'] ?? null,
                'is_active' => true,
            ]);

            return redirect()->back()
                ->with('success', 'Đã tải lên banner mới. Dữ liệu đã được kiểm tra đầy đủ trước khi lưu.');
        }, 'tạo banner');
    }

    public function toggle(Request $request, $id): RedirectResponse
    {
        $banner = Banner::find($id);

        if (! $banner) {
            return redirect()->back()
                ->with('error', 'Banner này không còn tồn tại. Vui lòng tải lại danh sách trước khi thao tác.');
        }

        return $this->runCrudOperation(function () use ($request, $banner): RedirectResponse {
            $this->transaction(function () use ($request, $banner): void {
                $lockedBanner = $this->lockForCrud($banner);
                $this->assertFreshRecord($request, $lockedBanner, 'banner');
                $lockedBanner->update(['is_active' => ! $lockedBanner->is_active]);
            });

            return redirect()->back()
                ->with('success', 'Đã đổi trạng thái hiển thị banner. Hệ thống đã kiểm tra phiên chỉnh sửa trước khi lưu.');
        }, 'đổi trạng thái banner');
    }

    public function destroy($id): RedirectResponse
    {
        $banner = Banner::find($id);

        if (! $banner) {
            return redirect()->back()
                ->with('error', 'Banner này đã được xóa trước đó. Danh sách đã được làm mới để tránh xóa nhầm.');
        }

        return $this->runCrudOperation(function () use ($banner): RedirectResponse {
            $imageUrl = null;

            $this->transaction(function () use ($banner, &$imageUrl): void {
                $lockedBanner = $this->lockForCrud($banner);
                $imageUrl = $lockedBanner->image_url;
                $lockedBanner->delete();
            });

            if ($imageUrl) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $imageUrl));
            }

            return redirect()->back()
                ->with('success', 'Đã xóa banner. Tệp ảnh liên quan cũng đã được dọn dẹp an toàn.');
        }, 'xóa banner');
    }
}
