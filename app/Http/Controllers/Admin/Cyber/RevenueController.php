<?php

namespace App\Http\Controllers\Admin\Cyber;

use App\Http\Controllers\Controller;
use App\Models\Cyber\Order;
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

        // Base query for orders with revenue statuses
        $baseQuery = Order::whereIn('status', ['delivered', 'approved', 'preparing', 'ready', 'on_delivery'])
            ->whereBetween('created_at', [$start, $end]);

        // Total revenue for period
        $totalRevenue = (float) $baseQuery->sum('total_amount');

        // Revenue by status
        $revenueByStatus = Order::whereIn('status', ['delivered', 'approved', 'preparing', 'ready', 'on_delivery'])
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw('status, SUM(total_amount) as revenue, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->status => [
                    'revenue' => (float) $item->revenue,
                    'count' => $item->count,
                ]];
            });

        // Daily breakdown
        $dailyBreakdown = Order::whereIn('status', ['delivered', 'approved', 'preparing', 'ready', 'on_delivery'])
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as revenue, COUNT(*) as count')
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
            $weeklyBreakdown = Order::whereIn('status', ['delivered', 'approved', 'preparing', 'ready', 'on_delivery'])
                ->whereBetween('created_at', [$start, $end])
                ->selectRaw('YEARWEEK(created_at) as week, SUM(total_amount) as revenue, COUNT(*) as count')
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
            $monthlyBreakdown = Order::whereIn('status', ['delivered', 'approved', 'preparing', 'ready', 'on_delivery'])
                ->whereBetween('created_at', [$start, $end])
                ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(total_amount) as revenue, COUNT(*) as count')
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

        // Total orders count
        $totalOrders = $baseQuery->count();

        // Average order value
        $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        return view('admin.cyber.revenue', compact(
            'totalRevenue',
            'totalOrders',
            'averageOrderValue',
            'revenueByStatus',
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
