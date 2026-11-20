<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(Request $request): View
    {
        return view('cart.index', ['cart' => $request->user()->cart?->load('items.product') ?? new Cart(['items' => collect()])]);
    }

    public function store(Request $request, Product $product): RedirectResponse
    {
        $cart = Cart::firstOrCreate(['user_id' => $request->user()->id]);
        $item = $cart->items()->firstOrNew(['product_id' => $product->id]);
        $item->quantity = min($product->stock, ($item->quantity ?? 0) + (int) $request->integer('quantity', 1));
        $item->save();

        return back();
    }

    public function destroy(Request $request, CartItem $cartItem): RedirectResponse
    {
        abort_unless($cartItem->cart->user_id === $request->user()->id, 403);
        $cartItem->delete();

        return back();
    }
}
