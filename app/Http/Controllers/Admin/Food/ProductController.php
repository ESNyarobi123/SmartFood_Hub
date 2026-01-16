<?php

namespace App\Http\Controllers\Admin\Food;

use App\Http\Controllers\Controller;
use App\Models\Food\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        if ($request->filled('available')) {
            $query->where('is_available', $request->available === 'yes');
        }

        $products = $query->ordered()->paginate(15);

        return view('admin.food.products.index', compact('products'));
    }

    public function create(): View
    {
        return view('admin.food.products.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
            'stock_quantity' => 'required|integer|min:0',
            'is_available' => 'boolean',
            'can_be_customized' => 'boolean',
            'image' => 'nullable|image|max:2048',
        ]);

        $validated['is_available'] = $request->boolean('is_available', true);
        $validated['can_be_customized'] = $request->boolean('can_be_customized', true);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('food-products', 'public');
        }

        Product::create($validated);

        return redirect()
            ->route('admin.food.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function edit(Product $product): View
    {
        return view('admin.food.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
            'stock_quantity' => 'required|integer|min:0',
            'is_available' => 'boolean',
            'can_be_customized' => 'boolean',
            'image' => 'nullable|image|max:2048',
        ]);

        $validated['is_available'] = $request->boolean('is_available');
        $validated['can_be_customized'] = $request->boolean('can_be_customized');

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('food-products', 'public');
        }

        $product->update($validated);

        return redirect()
            ->route('admin.food.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()
            ->route('admin.food.products.index')
            ->with('success', 'Product deleted successfully.');
    }
}
