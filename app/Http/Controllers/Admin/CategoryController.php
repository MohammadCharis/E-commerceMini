<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        return view('admin.categories.index', ['categories' => Category::withCount('products')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate(['name' => ['required', 'unique:categories,name']]);
        Category::create($request->only('name'));

        return back();
    }

    public function destroy(Category $category): RedirectResponse
    {
        $category->delete();

        return back();
    }
}
