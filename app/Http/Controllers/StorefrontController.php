<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;

class StorefrontController extends Controller
{
    public function index(): View
    {
        return view('storefront.index', ['products' => Product::where('is_active', true)->with('category')->latest()->paginate(12), 'categories' => Category::all()]);
    }

    public function show(Product $product): View
    {
        return view('storefront.show', compact('product'));
    }
}
