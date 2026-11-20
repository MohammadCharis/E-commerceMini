<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(): View
    {
        return view('admin.orders.index', ['orders' => Order::with('user')->latest()->get()]);
    }

    public function update(Request $request, Order $order): RedirectResponse
    {
        $request->validate(['status' => ['required', 'in:pending,processing,shipped,completed,cancelled']]);
        $order->update(['status' => $request->status]);

        return back();
    }
}
