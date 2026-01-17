<?php

namespace App\Http\Controllers\Food;

use App\Http\Controllers\Controller;
use App\Models\Food\Package;
use App\Models\Food\Product;
use Illuminate\View\View;

class PackageController extends Controller
{
    /**
     * Show the Monana Market landing page.
     */
    public function index(): View
    {
        $packages = Package::active()
            ->withCount('items')
            ->ordered()
            ->get();

        $products = Product::available()
            ->inStock()
            ->ordered()
            ->take(8)
            ->get();

        return view('food.index', compact('packages', 'products'));
    }

    /**
     * Show all packages.
     */
    public function packages(): View
    {
        $packages = Package::active()
            ->with('items.product')
            ->ordered()
            ->get();

        return view('food.packages', compact('packages'));
    }

    /**
     * Show package details.
     */
    public function show(Package $package): View
    {
        $package->load('items.product');

        return view('food.package', compact('package'));
    }
}
