<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        return view('admin.products.index', ['products' => Product::with('category')->latest()->get()]);
    }

    public function create(): View
    {
        return view('admin.products.form', ['product' => new Product, 'categories' => Category::all()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate(['category_id' => ['required', 'exists:categories,id'], 'name' => ['required', 'max:255'], 'description' => ['nullable'], 'price' => ['required', 'numeric', 'min:0'], 'stock' => ['required', 'integer', 'min:0']]);
        $data['slug'] = Str::slug($data['name']).'-'.Str::lower(Str::random(5));
        Product::create($data);

        return redirect()->route('admin.products.index');
    }

    public function edit(Product $product): View
    {
        return view('admin.products.form', ['product' => $product, 'categories' => Category::all()]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $request->validate(['category_id' => ['required', 'exists:categories,id'], 'name' => ['required', 'max:255'], 'description' => ['nullable'], 'price' => ['required', 'numeric', 'min:0'], 'stock' => ['required', 'integer', 'min:0']]);
        $product->update($data);

        return redirect()->route('admin.products.index');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return back();
    }
}
