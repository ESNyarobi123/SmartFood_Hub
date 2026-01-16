<?php

namespace App\Http\Controllers\Admin\Food;

use App\Http\Controllers\Controller;
use App\Models\Food\Package;
use App\Models\Food\PackageItem;
use App\Models\Food\PackageRule;
use App\Models\Food\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PackageController extends Controller
{
    public function index(): View
    {
        $packages = Package::withCount([
            'subscriptions as active_subscriptions' => fn ($q) => $q->where('status', 'active'),
            'items',
        ])->ordered()->paginate(15);

        return view('admin.food.packages.index', compact('packages'));
    }

    public function create(): View
    {
        $products = Product::available()->ordered()->get();

        return view('admin.food.packages.create', compact('products'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'duration_type' => 'required|in:weekly,monthly',
            'deliveries_per_week' => 'required|integer|min:1|max:7',
            'delivery_days' => 'required|array|min:1',
            'delivery_days.*' => 'integer|min:0|max:6',
            'customization_cutoff_time' => 'required|date_format:H:i',
            'is_active' => 'boolean',
            'image' => 'nullable|image|max:2048',
            'items' => 'array',
            'items.*.product_id' => 'required|exists:food_products,id',
            'items.*.default_quantity' => 'required|numeric|min:0.1',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['duration_days'] = $validated['duration_type'] === 'weekly' ? 7 : 30;

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('food-packages', 'public');
        }

        $package = Package::create($validated);

        // Add items
        if (! empty($validated['items'])) {
            foreach ($validated['items'] as $item) {
                PackageItem::create([
                    'package_id' => $package->id,
                    'product_id' => $item['product_id'],
                    'default_quantity' => $item['default_quantity'],
                ]);
            }
        }

        return redirect()
            ->route('admin.food.packages.index')
            ->with('success', 'Package created successfully.');
    }

    public function edit(Package $package): View
    {
        $package->load('items.product');
        $products = Product::available()->ordered()->get();

        return view('admin.food.packages.edit', compact('package', 'products'));
    }

    public function update(Request $request, Package $package): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'duration_type' => 'required|in:weekly,monthly',
            'deliveries_per_week' => 'required|integer|min:1|max:7',
            'delivery_days' => 'required|array|min:1',
            'delivery_days.*' => 'integer|min:0|max:6',
            'customization_cutoff_time' => 'required|date_format:H:i',
            'is_active' => 'boolean',
            'image' => 'nullable|image|max:2048',
            'items' => 'array',
            'items.*.product_id' => 'required|exists:food_products,id',
            'items.*.default_quantity' => 'required|numeric|min:0.1',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['duration_days'] = $validated['duration_type'] === 'weekly' ? 7 : 30;

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('food-packages', 'public');
        }

        $package->update($validated);

        // Update items
        $package->items()->delete();
        if (! empty($validated['items'])) {
            foreach ($validated['items'] as $item) {
                PackageItem::create([
                    'package_id' => $package->id,
                    'product_id' => $item['product_id'],
                    'default_quantity' => $item['default_quantity'],
                ]);
            }
        }

        return redirect()
            ->route('admin.food.packages.index')
            ->with('success', 'Package updated successfully.');
    }

    public function destroy(Package $package): RedirectResponse
    {
        // Check if there are active subscriptions
        if ($package->subscriptions()->where('status', 'active')->exists()) {
            return redirect()
                ->back()
                ->with('error', 'Cannot delete package with active subscriptions.');
        }

        $package->delete();

        return redirect()
            ->route('admin.food.packages.index')
            ->with('success', 'Package deleted successfully.');
    }

    public function rules(Package $package): View
    {
        $package->load(['rules.fromProduct', 'rules.toProduct', 'items.product']);
        $products = Product::available()->ordered()->get();

        return view('admin.food.packages.rules', compact('package', 'products'));
    }

    public function storeRule(Request $request, Package $package): RedirectResponse
    {
        $validated = $request->validate([
            'from_product_id' => 'required|exists:food_products,id',
            'to_product_id' => 'required|exists:food_products,id|different:from_product_id',
            'adjustment_type' => 'required|in:fixed,percentage',
            'adjustment_value' => 'required|numeric',
            'is_allowed' => 'boolean',
        ]);

        $validated['is_allowed'] = $request->boolean('is_allowed', true);
        $validated['package_id'] = $package->id;

        PackageRule::updateOrCreate(
            [
                'package_id' => $package->id,
                'from_product_id' => $validated['from_product_id'],
                'to_product_id' => $validated['to_product_id'],
            ],
            $validated
        );

        return redirect()
            ->back()
            ->with('success', 'Rule saved successfully.');
    }

    public function destroyRule(PackageRule $rule): RedirectResponse
    {
        $rule->delete();

        return redirect()
            ->back()
            ->with('success', 'Rule deleted successfully.');
    }
}
