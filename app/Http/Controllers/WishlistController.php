<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Display a listing of the user's wishlist.
     */
    public function index()
    {
        $wishlists = Wishlist::query()
            ->with('product')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('user.wishlist', [
            'wishlists' => $wishlists
        ]);
    }

    /**
     * Store a newly created resource in storage (Add to wishlist).
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $wishlist = Wishlist::updateOrCreate([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã thêm sản phẩm vào danh sách yêu thích.',
                'in_wishlist' => true
            ]);
        }

        return back()->with('success', 'Đã thêm sản phẩm vào danh sách yêu thích.');
    }

    /**
     * Remove the specified resource from storage (Remove from wishlist).
     */
    public function destroy(Request $request, $id)
    {
        // $id can be either wishlist id or product id
        $wishlist = Wishlist::where('user_id', Auth::id())
            ->where(function ($query) use ($id) {
                $query->where('id', $id)
                      ->orWhere('product_id', $id);
            })
            ->first();

        if ($wishlist) {
            $wishlist->delete();
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã xóa sản phẩm khỏi danh sách yêu thích.',
                'in_wishlist' => false
            ]);
        }

        return back()->with('success', 'Đã xóa sản phẩm khỏi danh sách yêu thích.');
    }
}
