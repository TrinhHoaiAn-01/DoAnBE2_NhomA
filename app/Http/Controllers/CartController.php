<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controller CartController
 *
 * Quản lý giỏ hàng của người dùng (lưu trữ trong Session).
 * Các chức năng bao gồm: Xem giỏ hàng, thêm sản phẩm, cập nhật số lượng, xóa sản phẩm và mua ngay.
 */
class CartController extends Controller
{
    /**
     * Hiển thị danh sách sản phẩm trong giỏ hàng và tổng tiền.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request): View
    {
        return view('cart.index', [
            'items' => $this->cartItems($request), // Lấy danh sách sản phẩm thực tế trong giỏ
            'total' => $this->cartTotal($request), // Tính tổng số tiền giỏ hàng
        ]);
    }

    /**
     * Thêm một sản phẩm vào giỏ hàng.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function add(Request $request, Product $product): RedirectResponse
    {
        // 1. Validate số lượng nhập vào (mặc định tối thiểu 1, tối đa 99)
        $data = $request->validate([
            'quantity' => ['nullable', 'integer', 'min:1', 'max:99'],
        ]);

        $quantity = (int) ($data['quantity'] ?? 1);
        
        // 2. Lấy giỏ hàng hiện tại từ Session (nếu chưa có thì trả về mảng rỗng)
        $cart = $request->session()->get('cart', []);
        
        // 3. Tính toán số lượng sản phẩm mới trong giỏ hàng
        $current = (int) ($cart[$product->id] ?? 0);
        // Đảm bảo số lượng không vượt quá số lượng tồn kho (stock) của sản phẩm
        $cart[$product->id] = min($current + $quantity, max($product->stock, 1));

        // 4. Lưu giỏ hàng mới cập nhật trở lại Session
        $request->session()->put('cart', $cart);

        return to_route('cart.index')->with('status', 'Đã thêm sản phẩm vào giỏ hàng.');
    }

    /**
     * Chức năng "Mua ngay" sản phẩm (Đưa sản phẩm vào giỏ hàng độc chiếm và chuyển đến trang thanh toán).
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function buyNow(Request $request, Product $product): RedirectResponse
    {
        // 1. Validate số lượng
        $data = $request->validate([
            'quantity' => ['nullable', 'integer', 'min:1', 'max:99'],
        ]);

        // Đảm bảo số lượng mua không lớn hơn lượng tồn kho thực tế
        $quantity = min((int) ($data['quantity'] ?? 1), max($product->stock, 1));

        // 2. Làm mới giỏ hàng trong Session chỉ chứa duy nhất sản phẩm này
        $request->session()->put('cart', [
            $product->id => $quantity,
        ]);

        // 3. Chuyển hướng trực tiếp đến trang thanh toán (checkout)
        return to_route('checkout.index')->with('status', 'Sản phẩm đã sẵn sàng để đặt hàng.');
    }

    /**
     * Cập nhật số lượng của một sản phẩm trong giỏ hàng.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        // 1. Bắt buộc phải có số lượng hợp lệ từ request
        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        // 2. Lấy giỏ hàng hiện tại
        $cart = $request->session()->get('cart', []);
        
        // 3. Cập nhật số lượng sản phẩm và kiểm tra giới hạn tồn kho
        $cart[$product->id] = min((int) $data['quantity'], max($product->stock, 1));

        // 4. Lưu lại vào Session
        $request->session()->put('cart', $cart);

        return to_route('cart.index')->with('status', 'Đã cập nhật giỏ hàng.');
    }

    /**
     * Xóa hoàn toàn một sản phẩm ra khỏi giỏ hàng.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove(Request $request, Product $product): RedirectResponse
    {
        // 1. Lấy giỏ hàng từ Session
        $cart = $request->session()->get('cart', []);
        
        // 2. Loại bỏ khóa sản phẩm khỏi mảng giỏ hàng
        unset($cart[$product->id]);

        // 3. Lưu lại giỏ hàng đã cập nhật vào Session
        $request->session()->put('cart', $cart);

        return to_route('cart.index')->with('status', 'Đã xóa sản phẩm khỏi giỏ hàng.');
    }

    /**
     * Lấy danh sách chi tiết các sản phẩm trong giỏ hàng hiện tại (truy vấn Database).
     *
     * @param \Illuminate\Http\Request $request
     * @return array Danh sách chi tiết gồm đối tượng Product, số lượng và tổng tiền từng sản phẩm
     */
    private function cartItems(Request $request): array
    {
        $cart = $request->session()->get('cart', []);
        
        // Truy vấn thông tin chi tiết các sản phẩm có ID trong giỏ hàng từ CSDL
        $products = Product::query()
            ->with('category')
            ->whereIn('id', array_keys($cart))
            ->get()
            ->keyBy('id');

        $items = [];

        // Duyệt qua từng sản phẩm trong giỏ hàng để cấu trúc lại thông tin hiển thị
        foreach ($cart as $productId => $quantity) {
            $product = $products->get((int) $productId);

            if (! $product) {
                continue;
            }

            $items[] = [
                'product' => $product,
                'quantity' => (int) $quantity,
                'subtotal' => (float) $product->price * (int) $quantity, // Tính thành tiền của sản phẩm này
            ];
        }

        return $items;
    }

    /**
     * Tính tổng thành tiền của toàn bộ giỏ hàng.
     *
     * @param \Illuminate\Http\Request $request
     * @return float Tổng giá trị giỏ hàng
     */
    private function cartTotal(Request $request): float
    {
        return collect($this->cartItems($request))->sum('subtotal');
    }
}

