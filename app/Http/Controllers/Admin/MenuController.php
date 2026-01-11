<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\FoodItem;
use App\Models\KitchenProduct;
use App\Models\SubscriptionPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function index(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $foodCategories = Category::where('type', 'food')->withCount('foodItems')->get();
        $kitchenCategories = Category::where('type', 'kitchen')->withCount('kitchenProducts')->get();
        $subscriptionPackages = SubscriptionPackage::all();

        return view('admin.menu.index', compact('foodCategories', 'kitchenCategories', 'subscriptionPackages'));
    }

    // Categories
    public function createCategory(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin.menu.categories.create');
    }

    public function storeCategory(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:food,kitchen',
            'is_active' => 'boolean',
        ]);

        Category::create($validated);

        return redirect()->route('admin.menu.index')
            ->with('success', 'Category created successfully!');
    }

    public function editCategory(Category $category): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin.menu.categories.edit', compact('category'));
    }

    public function updateCategory(Request $request, Category $category): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $category->update($validated);

        return redirect()->route('admin.menu.index')
            ->with('success', 'Category updated successfully!');
    }

    public function destroyCategory(Category $category): \Illuminate\Http\RedirectResponse
    {
        // Check if category has items
        if ($category->type === 'food' && $category->foodItems()->count() > 0) {
            return redirect()->route('admin.menu.index')
                ->with('error', 'Cannot delete category with existing food items. Please remove all items first.');
        }

        if ($category->type === 'kitchen' && $category->kitchenProducts()->count() > 0) {
            return redirect()->route('admin.menu.index')
                ->with('error', 'Cannot delete category with existing kitchen products. Please remove all products first.');
        }

        $category->delete();

        return redirect()->route('admin.menu.index')
            ->with('success', 'Category deleted successfully!');
    }

    // Food Items
    public function createFoodItem(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $categories = Category::where('type', 'food')->where('is_active', true)->get();

        return view('admin.menu.food-items.create', compact('categories'));
    }

    public function storeFoodItem(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'is_available' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('foods', 'public');
        }

        FoodItem::create($validated);

        return redirect()->route('admin.menu.index')
            ->with('success', 'Food item created successfully!');
    }

    public function editFoodItem(FoodItem $foodItem): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $categories = Category::where('type', 'food')->where('is_active', true)->get();

        return view('admin.menu.food-items.edit', compact('foodItem', 'categories'));
    }

    public function updateFoodItem(Request $request, FoodItem $foodItem): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'is_available' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            if ($foodItem->image) {
                Storage::disk('public')->delete($foodItem->image);
            }
            $validated['image'] = $request->file('image')->store('foods', 'public');
        }

        $foodItem->update($validated);

        return redirect()->route('admin.menu.index')
            ->with('success', 'Food item updated successfully!');
    }

    public function destroyFoodItem(FoodItem $foodItem): \Illuminate\Http\RedirectResponse
    {
        if ($foodItem->image) {
            Storage::disk('public')->delete($foodItem->image);
        }
        $foodItem->delete();

        return redirect()->route('admin.menu.index')
            ->with('success', 'Food item deleted successfully!');
    }

    // Kitchen Products
    public function createKitchenProduct(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $categories = Category::where('type', 'kitchen')->where('is_active', true)->get();

        return view('admin.menu.kitchen-products.create', compact('categories'));
    }

    public function storeKitchenProduct(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'is_available' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('kitchen-products', 'public');
        }

        KitchenProduct::create($validated);

        return redirect()->route('admin.menu.index')
            ->with('success', 'Kitchen product created successfully!');
    }

    public function editKitchenProduct(KitchenProduct $kitchenProduct): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $categories = Category::where('type', 'kitchen')->where('is_active', true)->get();

        return view('admin.menu.kitchen-products.edit', compact('kitchenProduct', 'categories'));
    }

    public function updateKitchenProduct(Request $request, KitchenProduct $kitchenProduct): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'is_available' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            if ($kitchenProduct->image) {
                Storage::disk('public')->delete($kitchenProduct->image);
            }
            $validated['image'] = $request->file('image')->store('kitchen-products', 'public');
        }

        $kitchenProduct->update($validated);

        return redirect()->route('admin.menu.index')
            ->with('success', 'Kitchen product updated successfully!');
    }

    public function destroyKitchenProduct(KitchenProduct $kitchenProduct): \Illuminate\Http\RedirectResponse
    {
        if ($kitchenProduct->image) {
            Storage::disk('public')->delete($kitchenProduct->image);
        }
        $kitchenProduct->delete();

        return redirect()->route('admin.menu.index')
            ->with('success', 'Kitchen product deleted successfully!');
    }

    // Subscription Packages
    public function createSubscriptionPackage(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin.menu.subscription-packages.create');
    }

    public function storeSubscriptionPackage(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_type' => 'required|in:weekly,monthly',
            'meals_per_week' => 'required|integer|min:1|max:7',
            'delivery_days' => 'nullable|array',
            'delivery_days.*' => 'integer|min:0|max:6',
            'is_active' => 'boolean',
        ]);

        SubscriptionPackage::create($validated);

        return redirect()->route('admin.menu.index')
            ->with('success', 'Subscription package created successfully!');
    }

    public function editSubscriptionPackage(SubscriptionPackage $subscriptionPackage): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin.menu.subscription-packages.edit', compact('subscriptionPackage'));
    }

    public function updateSubscriptionPackage(Request $request, SubscriptionPackage $subscriptionPackage): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_type' => 'required|in:weekly,monthly',
            'meals_per_week' => 'required|integer|min:1|max:7',
            'delivery_days' => 'nullable|array',
            'delivery_days.*' => 'integer|min:0|max:6',
            'is_active' => 'boolean',
        ]);

        $subscriptionPackage->update($validated);

        return redirect()->route('admin.menu.index')
            ->with('success', 'Subscription package updated successfully!');
    }

    public function destroySubscriptionPackage(SubscriptionPackage $subscriptionPackage): \Illuminate\Http\RedirectResponse
    {
        $subscriptionPackage->delete();

        return redirect()->route('admin.menu.index')
            ->with('success', 'Subscription package deleted successfully!');
    }
}

