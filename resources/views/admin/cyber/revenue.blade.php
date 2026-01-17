@extends('admin.layout')

@section('title', 'Revenue - Monana Food')
@section('subtitle', 'View revenue reports and analytics')

@section('content')
<div class="space-y-6">
    <!-- Period Selector -->
    <div class="card p-6">
        <form method="GET" action="{{ route('admin.cyber.revenue') }}" class="flex flex-col md:flex-row gap-4 items-end">
            <div class="flex-1 grid grid-cols-2 md:grid-cols-4 gap-3">
                <button type="submit" name="period" value="today" 
                    class="px-4 py-2 rounded-lg text-sm font-bold transition-all {{ $period === 'today' ? 'bg-[#00ffc8] text-[#0a0a0f] border-2 border-[#00ffc8] shadow-lg shadow-[#00ffc8]/50' : 'bg-white/5 text-[#a8b2c1] hover:bg-white/10 border-2 border-transparent' }}">
                    Today
                </button>
                <button type="submit" name="period" value="week" 
                    class="px-4 py-2 rounded-lg text-sm font-bold transition-all {{ $period === 'week' ? 'bg-[#00ffc8] text-[#0a0a0f] border-2 border-[#00ffc8] shadow-lg shadow-[#00ffc8]/50' : 'bg-white/5 text-[#a8b2c1] hover:bg-white/10 border-2 border-transparent' }}">
                    This Week
                </button>
                <button type="submit" name="period" value="month" 
                    class="px-4 py-2 rounded-lg text-sm font-bold transition-all {{ $period === 'month' ? 'bg-[#00ffc8] text-[#0a0a0f] border-2 border-[#00ffc8] shadow-lg shadow-[#00ffc8]/50' : 'bg-white/5 text-[#a8b2c1] hover:bg-white/10 border-2 border-transparent' }}">
                    This Month
                </button>
                <button type="submit" name="period" value="year" 
                    class="px-4 py-2 rounded-lg text-sm font-bold transition-all {{ $period === 'year' ? 'bg-[#00ffc8] text-[#0a0a0f] border-2 border-[#00ffc8] shadow-lg shadow-[#00ffc8]/50' : 'bg-white/5 text-[#a8b2c1] hover:bg-white/10 border-2 border-transparent' }}">
                    This Year
                </button>
            </div>
            <div class="flex gap-3">
                <input type="date" name="start_date" value="{{ $startDate }}" 
                    class="px-4 py-2 bg-white/5 border border-white/10 rounded-lg text-white text-sm focus:outline-none focus:border-[#00ffc8]"
                    placeholder="Start Date">
                <input type="date" name="end_date" value="{{ $endDate }}" 
                    class="px-4 py-2 bg-white/5 border border-white/10 rounded-lg text-white text-sm focus:outline-none focus:border-[#00ffc8]"
                    placeholder="End Date">
                <button type="submit" name="period" value="custom" 
                    class="px-6 py-2 bg-[#00ffc8] text-[#0a0a0f] rounded-lg text-sm font-medium hover:bg-[#00d9f5] transition-colors">
                    Apply
                </button>
            </div>
        </form>
        <p class="text-xs text-[#5c6b7f] mt-3">
            Period: {{ $start->format('M d, Y') }} - {{ $end->format('M d, Y') }}
        </p>
    </div>

    <!-- Revenue Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="card p-6 border-l-4 border-[#00ffc8]">
            <p class="text-xs text-[#6b6b6b] uppercase tracking-wider mb-2">Total Revenue</p>
            <p class="text-3xl font-bold text-white">TZS {{ number_format($totalRevenue, 0) }}</p>
        </div>
        <div class="card p-6 border-l-4 border-blue-500">
            <p class="text-xs text-[#6b6b6b] uppercase tracking-wider mb-2">Total Orders</p>
            <p class="text-3xl font-bold text-white">{{ number_format($totalOrders) }}</p>
        </div>
        <div class="card p-6 border-l-4 border-green-500">
            <p class="text-xs text-[#6b6b6b] uppercase tracking-wider mb-2">Average Order Value</p>
            <p class="text-3xl font-bold text-white">TZS {{ number_format($averageOrderValue, 0) }}</p>
        </div>
    </div>

    <!-- Revenue by Status -->
    @if($revenueByStatus->isNotEmpty())
    <div class="card p-6">
        <h3 class="text-lg font-bold text-white mb-4">Revenue by Order Status</h3>
        <div class="space-y-3">
            @foreach($revenueByStatus as $status => $data)
            <div class="flex items-center justify-between p-4 bg-white/5 rounded-lg">
                <div>
                    <p class="text-sm font-medium text-white capitalize">{{ str_replace('_', ' ', $status) }}</p>
                    <p class="text-xs text-[#5c6b7f]">{{ $data['count'] }} orders</p>
                </div>
                <p class="text-lg font-bold text-[#00ffc8]">TZS {{ number_format($data['revenue'], 0) }}</p>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Daily Breakdown -->
    @if($dailyBreakdown->isNotEmpty())
    <div class="card p-6">
        <h3 class="text-lg font-bold text-white mb-4">Daily Revenue Breakdown</h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-white/5">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-[#6b6b6b] uppercase">Date</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-[#6b6b6b] uppercase">Orders</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-[#6b6b6b] uppercase">Revenue</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @foreach($dailyBreakdown as $day)
                    <tr class="hover:bg-white/5">
                        <td class="px-4 py-3 text-sm text-white">{{ \Carbon\Carbon::parse($day['date'])->format('M d, Y') }}</td>
                        <td class="px-4 py-3 text-sm text-[#a8b2c1] text-right">{{ $day['count'] }}</td>
                        <td class="px-4 py-3 text-sm font-medium text-[#00ffc8] text-right">TZS {{ number_format($day['revenue'], 0) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Weekly Breakdown -->
    @if($weeklyBreakdown->isNotEmpty())
    <div class="card p-6">
        <h3 class="text-lg font-bold text-white mb-4">Weekly Revenue Breakdown</h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-white/5">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-[#6b6b6b] uppercase">Week</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-[#6b6b6b] uppercase">Payments</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-[#6b6b6b] uppercase">Revenue</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @foreach($weeklyBreakdown as $week)
                    <tr class="hover:bg-white/5">
                        <td class="px-4 py-3 text-sm text-white">Week {{ $week['week'] }}</td>
                        <td class="px-4 py-3 text-sm text-[#a8b2c1] text-right">{{ $week['count'] }}</td>
                        <td class="px-4 py-3 text-sm font-medium text-[#00ffc8] text-right">TZS {{ number_format($week['revenue'], 0) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Monthly Breakdown -->
    @if($monthlyBreakdown->isNotEmpty())
    <div class="card p-6">
        <h3 class="text-lg font-bold text-white mb-4">Monthly Revenue Breakdown</h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-white/5">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-[#6b6b6b] uppercase">Month</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-[#6b6b6b] uppercase">Payments</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-[#6b6b6b] uppercase">Revenue</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @foreach($monthlyBreakdown as $month)
                    <tr class="hover:bg-white/5">
                        <td class="px-4 py-3 text-sm text-white">{{ \Carbon\Carbon::parse($month['month'] . '-01')->format('F Y') }}</td>
                        <td class="px-4 py-3 text-sm text-[#a8b2c1] text-right">{{ $month['count'] }}</td>
                        <td class="px-4 py-3 text-sm font-medium text-[#00ffc8] text-right">TZS {{ number_format($month['revenue'], 0) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    @if($totalRevenue == 0)
    <div class="card p-12 text-center">
        <svg class="w-16 h-16 mx-auto text-[#5c6b7f] mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <p class="text-[#6b6b6b]">No revenue data for the selected period</p>
    </div>
    @endif
</div>
@endsection
