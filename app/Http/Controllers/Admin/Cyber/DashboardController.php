<?php

namespace App\Http\Controllers\Admin\Cyber;

use App\Http\Controllers\Controller;
use App\Models\Cyber\MealSlot;
use App\Models\Cyber\MenuItem;
use App\Models\Cyber\Order;
use App\Services\MealTimeService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private MealTimeService $mealTimeService
    ) {}

    public function index(): View
    {
        $stats = [
            'pending_orders' => Order::where('status', 'pending')->count(),
            'today_orders' => Order::whereDate('created_at', today())->count(),
            'preparing_orders' => Order::where('status', 'preparing')->count(),
            'delivered_today' => Order::where('status', 'delivered')
                ->whereDate('delivered_at', today())
                ->count(),
            'today_revenue' => Order::whereDate('created_at', today())
                ->whereIn('status', ['delivered', 'approved', 'preparing', 'ready', 'on_delivery'])
                ->sum('total_amount'),
            'total_menu_items' => MenuItem::count(),
            'available_items' => MenuItem::where('is_available', true)->count(),
        ];

        // Meal Slots with status
        $mealSlots = $this->mealTimeService->getSlotsWithStatus();

        // Orders by meal slot
        $ordersBySlot = [];
        foreach (MealSlot::all() as $slot) {
            $ordersBySlot[$slot->id] = [
                'name' => $slot->display_name,
                'pending' => Order::where('meal_slot_id', $slot->id)
                    ->where('status', 'pending')
                    ->whereDate('created_at', today())
                    ->count(),
                'total' => Order::where('meal_slot_id', $slot->id)
                    ->whereDate('created_at', today())
                    ->count(),
            ];
        }

        // Recent orders
        $recentOrders = Order::with(['user', 'mealSlot'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.cyber.dashboard', compact(
            'stats',
            'mealSlots',
            'ordersBySlot',
            'recentOrders'
        ));
    }
}
