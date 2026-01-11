<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\FoodItem;
use App\Models\KitchenProduct;
use App\Models\SubscriptionPackage;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $foodCategories = Category::where('type', 'food')
            ->where('is_active', true)
            ->with(['foodItems' => function ($query) {
                $query->where('is_available', true);
            }])
            ->get();

        $kitchenCategories = Category::where('type', 'kitchen')
            ->where('is_active', true)
            ->with(['kitchenProducts' => function ($query) {
                $query->where('is_available', true);
            }])
            ->get();

        $subscriptionPackages = SubscriptionPackage::where('is_active', true)->get();

        return view('home', compact('foodCategories', 'kitchenCategories', 'subscriptionPackages'));
    }
}
