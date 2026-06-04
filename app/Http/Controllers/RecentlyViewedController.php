<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class RecentlyViewedController extends Controller
{
    /**
     * Display recently viewed products list.
     */
    public function index(Request $request): View
    {
        $recentlyViewedIds = session()->get('recently_viewed', []);

        $products = collect();
        if (!empty($recentlyViewedIds)) {
            $products = Product::query()
                ->whereIn('id', $recentlyViewedIds)
                ->where('is_active', true)
                ->get()
                ->sortBy(fn($item) => array_search($item->id, $recentlyViewedIds))
                ->values();
        }

        return view('user.recently-viewed', [
            'products' => $products
        ]);
    }

    /**
     * Remove a product from recently viewed list.
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        $recentlyViewedIds = session()->get('recently_viewed', []);
        
        $recentlyViewedIds = array_values(array_filter($recentlyViewedIds, fn($val) => (int)$val !== (int)$id));
        
        session()->put('recently_viewed', $recentlyViewedIds);

        return response()->json([
            'success' => true,
            'message' => 'Đã xoá sản phẩm khỏi danh sách đã xem.',
            'count' => count($recentlyViewedIds)
        ]);
    }

    /**
     * Clear all recently viewed products.
     */
    public function clear(Request $request)
    {
        session()->forget('recently_viewed');

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã xoá toàn bộ lịch sử đã xem.'
            ]);
        }

        return redirect()->route('recently-viewed.index')->with('success', 'Đã xoá toàn bộ lịch sử đã xem.');
    }
}
