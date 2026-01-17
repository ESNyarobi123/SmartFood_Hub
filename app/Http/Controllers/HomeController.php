<?php

namespace App\Http\Controllers;

use App\Models\Cyber\MenuItem;
use App\Models\Food\Package;
use App\Models\Food\Product;
use App\Services\MealTimeService;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(
        private MealTimeService $mealTimeService
    ) {}

    public function index(): View
    {
        // Monana Food data
        $mealSlots = $this->mealTimeService->getSlotsWithStatus();
        $cyberItemsCount = MenuItem::available()->count();

        // Food service data
        $packagesCount = Package::active()->count();
        $productsCount = Product::available()->count();

        // Featured items for each service
        $featuredCyberItems = MenuItem::available()
            ->ordered()
            ->take(4)
            ->get();

        $featuredPackages = Package::active()
            ->ordered()
            ->take(3)
            ->get();

        return view('home', compact(
            'mealSlots',
            'cyberItemsCount',
            'packagesCount',
            'productsCount',
            'featuredCyberItems',
            'featuredPackages'
        ));
    }
}
