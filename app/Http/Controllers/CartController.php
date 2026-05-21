<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(Request $request): View
    {
        return view('cart.index', [
            'items' => $this->cartItems($request),
            'total' => $this->cartTotal($request),
        ]);
    }

    public function add(Request $request, Product $product): RedirectResponse
    {
        $data = $request->validate([
            'quantity' => ['nullable', 'integer', 'min:1', 'max:99'],
        ]);

        $quantity = (int) ($data['quantity'] ?? 1);
        $cart = $request->session()->get('cart', []);
        $current = (int) ($cart[$product->id] ?? 0);
        $cart[$product->id] = min($current + $quantity, max($product->stock, 1));

        $request->session()->put('cart', $cart);

        return to_route('cart.index')->with('status', 'Đã thêm sản phẩm vào giỏ hàng.');
    }

    public function buyNow(Request $request, Product $product): RedirectResponse
    {
        $data = $request->validate([
            'quantity' => ['nullable', 'integer', 'min:1', 'max:99'],
        ]);

        $quantity = min((int) ($data['quantity'] ?? 1), max($product->stock, 1));

        $request->session()->put('cart', [
            $product->id => $quantity,
        ]);

        return to_route('checkout.index')->with('status', 'Sản phẩm đã sẵn sàng để đặt hàng.');
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $cart = $request->session()->get('cart', []);
        $cart[$product->id] = min((int) $data['quantity'], max($product->stock, 1));

        $request->session()->put('cart', $cart);

        return to_route('cart.index')->with('status', 'Đã cập nhật giỏ hàng.');
    }

    public function remove(Request $request, Product $product): RedirectResponse
    {
        $cart = $request->session()->get('cart', []);
        unset($cart[$product->id]);

        $request->session()->put('cart', $cart);

        return to_route('cart.index')->with('status', 'Đã xóa sản phẩm khỏi giỏ hàng.');
    }

    private function cartItems(Request $request): array
    {
        $cart = $request->session()->get('cart', []);
        $products = Product::query()
            ->with('category')
            ->whereIn('id', array_keys($cart))
            ->get()
            ->keyBy('id');

        $items = [];

        foreach ($cart as $productId => $quantity) {
            $product = $products->get((int) $productId);

            if (! $product) {
                continue;
            }

            $items[] = [
                'product' => $product,
                'quantity' => (int) $quantity,
                'subtotal' => (float) $product->price * (int) $quantity,
            ];
        }

        return $items;
    }

    private function cartTotal(Request $request): float
    {
        return collect($this->cartItems($request))->sum('subtotal');
    }
}
