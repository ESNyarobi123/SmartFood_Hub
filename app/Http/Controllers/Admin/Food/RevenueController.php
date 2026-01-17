<?php

namespace App\Http\Controllers\Admin\Food;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RevenueController extends Controller
{
    public function index(Request $request): View
    {
        // Get period from request or default to 'month'
        $period = $request->get('period', 'month');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        // Calculate date range based on period
        $dateRange = $this->getDateRange($period, $startDate, $endDate);
        $start = $dateRange['start'];
        $end = $dateRange['end'];

        // Revenue from Orders (paid orders)
        $orderRevenue = Payment::where('service_type', 'food')
            ->where('status', 'paid')
            ->whereNotNull('food_order_id')
            ->whereBetween('created_at', [$start, $end])
            ->sum('amount');

        // Revenue from Subscriptions (paid subscriptions)
        $subscriptionRevenue = Payment::where('service_type', 'food')
            ->where('status', 'paid')
            ->whereNotNull('food_subscription_id')
            ->whereBetween('created_at', [$start, $end])
            ->sum('amount');

        // Total revenue
        $totalRevenue = (float) ($orderRevenue + $subscriptionRevenue);

        // Revenue breakdown by type
        $revenueByType = [
            'orders' => [
                'revenue' => (float) $orderRevenue,
                'count' => Payment::where('service_type', 'food')
                    ->where('status', 'paid')
                    ->whereNotNull('food_order_id')
                    ->whereBetween('created_at', [$start, $end])
                    ->count(),
            ],
            'subscriptions' => [
                'revenue' => (float) $subscriptionRevenue,
                'count' => Payment::where('service_type', 'food')
                    ->where('status', 'paid')
                    ->whereNotNull('food_subscription_id')
                    ->whereBetween('created_at', [$start, $end])
                    ->count(),
            ],
        ];

        // Daily breakdown
        $dailyBreakdown = Payment::where('service_type', 'food')
            ->where('status', 'paid')
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw('DATE(created_at) as date, SUM(amount) as revenue, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function ($item) {
                return [
                    'date' => Carbon::parse($item->date)->format('Y-m-d'),
                    'revenue' => (float) $item->revenue,
                    'count' => $item->count,
                ];
            });

        // Weekly breakdown (if period is month or year)
        $weeklyBreakdown = collect();
        if (in_array($period, ['month', 'year'])) {
            $weeklyBreakdown = Payment::where('service_type', 'food')
                ->where('status', 'paid')
                ->whereBetween('created_at', [$start, $end])
                ->selectRaw('YEARWEEK(created_at) as week, SUM(amount) as revenue, COUNT(*) as count')
                ->groupBy('week')
                ->orderBy('week')
                ->get()
                ->map(function ($item) {
                    return [
                        'week' => $item->week,
                        'revenue' => (float) $item->revenue,
                        'count' => $item->count,
                    ];
                });
        }

        // Monthly breakdown (if period is year)
        $monthlyBreakdown = collect();
        if ($period === 'year') {
            $monthlyBreakdown = Payment::where('service_type', 'food')
                ->where('status', 'paid')
                ->whereBetween('created_at', [$start, $end])
                ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(amount) as revenue, COUNT(*) as count')
                ->groupBy('month')
                ->orderBy('month')
                ->get()
                ->map(function ($item) {
                    return [
                        'month' => $item->month,
                        'revenue' => (float) $item->revenue,
                        'count' => $item->count,
                    ];
                });
        }

        // Total payments count
        $totalPayments = Payment::where('service_type', 'food')
            ->where('status', 'paid')
            ->whereBetween('created_at', [$start, $end])
            ->count();

        // Average payment value
        $averagePaymentValue = $totalPayments > 0 ? $totalRevenue / $totalPayments : 0;

        return view('admin.food.revenue', compact(
            'totalRevenue',
            'totalPayments',
            'averagePaymentValue',
            'revenueByType',
            'dailyBreakdown',
            'weeklyBreakdown',
            'monthlyBreakdown',
            'period',
            'start',
            'end',
            'startDate',
            'endDate'
        ));
    }

    private function getDateRange(string $period, ?string $startDate, ?string $endDate): array
    {
        // If custom dates provided, use them
        if ($startDate && $endDate) {
            return [
                'start' => Carbon::parse($startDate)->startOfDay(),
                'end' => Carbon::parse($endDate)->endOfDay(),
            ];
        }

        // Otherwise use predefined periods
        return match ($period) {
            'today' => [
                'start' => Carbon::today()->startOfDay(),
                'end' => Carbon::today()->endOfDay(),
            ],
            'week' => [
                'start' => Carbon::now()->startOfWeek(),
                'end' => Carbon::now()->endOfWeek(),
            ],
            'month' => [
                'start' => Carbon::now()->startOfMonth(),
                'end' => Carbon::now()->endOfMonth(),
            ],
            'year' => [
                'start' => Carbon::now()->startOfYear(),
                'end' => Carbon::now()->endOfYear(),
            ],
            default => [
                'start' => Carbon::now()->startOfMonth(),
                'end' => Carbon::now()->endOfMonth(),
            ],
        };
    }
}
