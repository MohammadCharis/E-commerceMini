<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function create(Request $request): View
    {
        return view('checkout.index', ['cart' => $request->user()->cart?->load('items.product'), 'addresses' => $request->user()->addresses]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate(['address_id' => ['required', 'exists:addresses,id'], 'method' => ['required', 'in:cash,card']]);
        $address = Address::where('id', $data['address_id'])->where('user_id', $request->user()->id)->firstOrFail();
        $cart = Cart::where('user_id', $request->user()->id)->with('items.product')->firstOrFail();
        abort_if($cart->items->isEmpty(), 422, 'Cart is empty');
        $order = DB::transaction(function () use ($cart, $request, $address, $data): Order {
            $order = $request->user()->orders()->create(['invoice_no' => 'INV-'.strtoupper(Str::random(10)), 'total' => $cart->total, 'shipping_address' => $address->full_address, 'status' => 'pending']);
            foreach ($cart->items as $item) {
                $order->items()->create(['product_id' => $item->product_id, 'quantity' => $item->quantity, 'price' => $item->product->price]);
                $item->product->decrement('stock', $item->quantity);
            } $order->payment()->create(['method' => $data['method'], 'status' => $data['method'] === 'card' ? 'paid' : 'pending', 'paid_at' => $data['method'] === 'card' ? now() : null]);
            $cart->items()->delete();

            return $order;
        });

        return redirect()->route('orders.show', $order);
    }
}
