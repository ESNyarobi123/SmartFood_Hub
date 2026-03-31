<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Monana Operations — {{ $todayStr }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0a0a0f 0%, #0d0d15 50%, #12121a 100%);
            background-attachment: fixed;
            color: #ffffff;
            min-height: 100vh;
        }
        body::before {
            content: '';
            position: fixed;
            top: -50%; left: -50%;
            width: 200%; height: 200%;
            background:
                radial-gradient(circle at 20% 80%, rgba(139, 92, 246, 0.06) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(0, 255, 200, 0.06) 0%, transparent 50%),
                radial-gradient(circle at 50% 50%, rgba(255, 123, 84, 0.04) 0%, transparent 40%);
            animation: float 20s ease-in-out infinite;
            pointer-events: none; z-index: 0;
        }
        @keyframes float {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(0, 3%); }
        }
        .glass { background: rgba(255,255,255,0.03); backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.08); }
        .glass:hover { border-color: rgba(139,92,246,0.25); }
        .stat-glow-cyan { background: linear-gradient(135deg, rgba(0,255,200,0.08), rgba(6,182,212,0.03)); border-color: rgba(0,255,200,0.2); }
        .stat-glow-orange { background: linear-gradient(135deg, rgba(255,123,84,0.08), rgba(254,225,64,0.03)); border-color: rgba(255,123,84,0.2); }
        .stat-glow-purple { background: linear-gradient(135deg, rgba(139,92,246,0.08), rgba(245,87,108,0.03)); border-color: rgba(139,92,246,0.2); }
        .stat-glow-green { background: linear-gradient(135deg, rgba(16,185,129,0.08), rgba(6,182,212,0.03)); border-color: rgba(16,185,129,0.2); }
        .badge-pending { background: rgba(245,158,11,0.15); color: #fbbf24; border: 1px solid rgba(245,158,11,0.3); }
        .badge-approved { background: rgba(59,130,246,0.15); color: #60a5fa; border: 1px solid rgba(59,130,246,0.3); }
        .badge-active { background: rgba(16,185,129,0.15); color: #34d399; border: 1px solid rgba(16,185,129,0.3); }
        .badge-preparing { background: rgba(139,92,246,0.15); color: #a78bfa; border: 1px solid rgba(139,92,246,0.3); }
        .badge-ready { background: rgba(6,182,212,0.15); color: #22d3ee; border: 1px solid rgba(6,182,212,0.3); }
        .badge-on_delivery { background: rgba(168,85,247,0.15); color: #c084fc; border: 1px solid rgba(168,85,247,0.3); }
        .badge-delivered { background: rgba(0,255,200,0.15); color: #00ffc8; border: 1px solid rgba(0,255,200,0.3); }
        .badge-cancelled { background: rgba(239,68,68,0.15); color: #f87171; border: 1px solid rgba(239,68,68,0.3); }
        .badge-rejected { background: rgba(239,68,68,0.15); color: #f87171; border: 1px solid rgba(239,68,68,0.3); }
        .badge-paused { background: rgba(107,114,128,0.2); color: #9ca3af; border: 1px solid rgba(107,114,128,0.3); }
        .badge-expired { background: rgba(107,114,128,0.15); color: #6b7280; border: 1px solid rgba(107,114,128,0.2); }
        .badge-swap { background: rgba(59,130,246,0.15); color: #60a5fa; border: 1px solid rgba(59,130,246,0.3); }
        .badge-remove { background: rgba(239,68,68,0.15); color: #f87171; border: 1px solid rgba(239,68,68,0.3); }
        .badge-pause { background: rgba(245,158,11,0.15); color: #fbbf24; border: 1px solid rgba(245,158,11,0.3); }
        .badge-add { background: rgba(16,185,129,0.15); color: #34d399; border: 1px solid rgba(16,185,129,0.3); }
        .tab-active { background: linear-gradient(135deg, rgba(139,92,246,0.2), rgba(139,92,246,0.05)); border-color: rgba(139,92,246,0.5); color: #fff; }
        .tab-inactive { background: rgba(255,255,255,0.02); border-color: rgba(255,255,255,0.08); color: #a8b2c1; }
        .tab-inactive:hover { border-color: rgba(139,92,246,0.3); color: #fff; }
        .pulse-dot { animation: pulse-dot 2s infinite; }
        @keyframes pulse-dot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(1.5); }
        }
        .order-row { transition: all 0.2s; }
        .order-row:hover { background: rgba(139,92,246,0.05); }

        /* Mobile card layout for order data */
        .mobile-card { display: none; }

        @media (max-width: 768px) {
            .desktop-table { display: none !important; }
            .mobile-card { display: block !important; }
        }
        @media (min-width: 769px) {
            .mobile-card { display: none !important; }
        }

        @media print {
            body { background: #fff !important; color: #000 !important; }
            body::before { display: none; }
            .glass { background: #f9fafb !important; border-color: #e5e7eb !important; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body class="min-h-screen pb-16">
    <div class="relative z-10">

        {{-- HEADER --}}
        <header class="sticky top-0 z-50" style="background: rgba(10,10,18,0.9); backdrop-filter: blur(20px); border-bottom: 1px solid rgba(255,255,255,0.06);">
            <div class="max-w-7xl mx-auto px-3 sm:px-6 py-3 sm:py-4">
                <div class="flex items-center justify-between gap-2">
                    <div class="flex items-center gap-2 sm:gap-3 min-w-0">
                        <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl flex items-center justify-center flex-shrink-0" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                            <span class="text-base sm:text-lg font-black text-white">M</span>
                        </div>
                        <div class="min-w-0">
                            <h1 class="text-base sm:text-xl font-bold text-white truncate">Monana Ops</h1>
                            <p class="text-[10px] sm:text-xs text-gray-400 truncate">
                                📅 {{ \Carbon\Carbon::parse($todayStr)->format('D, d M Y') }}
                                @if($isToday) · <span class="text-[#00ffc8]">Leo</span> {{ now()->format('H:i') }}
                                @else · <span class="text-yellow-400">Nyuma</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-1.5 sm:gap-2 no-print flex-shrink-0">
                        <form method="GET" class="flex items-center">
                            <input type="date" name="date" value="{{ $todayStr }}"
                                   class="w-[110px] sm:w-auto px-2 sm:px-3 py-1.5 sm:py-2 rounded-lg text-[10px] sm:text-xs font-semibold text-white"
                                   style="background: rgba(255,255,255,0.05) !important; border: 1px solid rgba(255,255,255,0.15) !important;"
                                   onchange="this.form.submit()">
                        </form>
                        <a href="{{ route('ops.daily', $token) }}" class="px-2 sm:px-3 py-1.5 sm:py-2 rounded-lg text-[10px] sm:text-xs font-semibold transition-all" style="background: rgba(245,158,11,0.15); border: 1px solid rgba(245,158,11,0.3); color: #fbbf24;">
                            Leo
                        </a>
                        <button onclick="location.reload()" class="px-2 sm:px-3 py-1.5 sm:py-2 rounded-lg text-[10px] sm:text-xs font-semibold transition-all hidden sm:block" style="background: rgba(139,92,246,0.15); border: 1px solid rgba(139,92,246,0.3); color: #a78bfa;">
                            🔄
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <div class="max-w-7xl mx-auto px-3 sm:px-6 pt-4 sm:pt-6 space-y-4 sm:space-y-6">

            {{-- STATS --}}
            <div class="grid grid-cols-3 sm:grid-cols-4 lg:grid-cols-7 gap-2 sm:gap-3">
                <div class="glass rounded-xl sm:rounded-2xl p-3 sm:p-4 stat-glow-cyan">
                    <p class="text-[10px] sm:text-xs text-gray-400 mb-0.5">🍽️ Cyber</p>
                    <p class="text-xl sm:text-2xl font-black text-[#00ffc8]">{{ $stats['total_cyber_orders'] }}</p>
                    <p class="text-[10px] sm:text-xs text-gray-500">{{ $stats['all_cyber_orders'] }} jumla</p>
                </div>
                <div class="glass rounded-xl sm:rounded-2xl p-3 sm:p-4 stat-glow-orange">
                    <p class="text-[10px] sm:text-xs text-gray-400 mb-0.5">🛒 Sokoni</p>
                    <p class="text-xl sm:text-2xl font-black text-[#ff7b54]">{{ $stats['total_food_orders'] }}</p>
                    <p class="text-[10px] sm:text-xs text-gray-500">{{ $stats['all_food_orders'] }} jumla</p>
                </div>
                <div class="glass rounded-xl sm:rounded-2xl p-3 sm:p-4 stat-glow-green">
                    <p class="text-[10px] sm:text-xs text-gray-400 mb-0.5">📦 Plans</p>
                    <p class="text-xl sm:text-2xl font-black text-emerald-400">{{ $stats['active_subscriptions'] }}</p>
                    <p class="text-[10px] sm:text-xs text-gray-500">{{ $stats['all_subscriptions'] }} total</p>
                </div>
                <div class="glass rounded-xl sm:rounded-2xl p-3 sm:p-4 stat-glow-purple">
                    <p class="text-[10px] sm:text-xs text-gray-400 mb-0.5">⏳ Pending</p>
                    <p class="text-xl sm:text-2xl font-black text-purple-400">{{ $stats['pending_cyber'] }}</p>
                </div>
                <div class="glass rounded-xl sm:rounded-2xl p-3 sm:p-4 stat-glow-green">
                    <p class="text-[10px] sm:text-xs text-gray-400 mb-0.5">✅ Delivered</p>
                    <p class="text-xl sm:text-2xl font-black text-[#00ffc8]">{{ $stats['delivered_cyber'] }}</p>
                </div>
                <div class="glass rounded-xl sm:rounded-2xl p-3 sm:p-4" style="background: linear-gradient(135deg, rgba(59,130,246,0.08), rgba(59,130,246,0.02)); border-color: rgba(59,130,246,0.2);">
                    <p class="text-[10px] sm:text-xs text-gray-400 mb-0.5">✏️ Changes</p>
                    <p class="text-xl sm:text-2xl font-black text-blue-400">{{ $stats['customizations_today'] }}</p>
                </div>
                <div class="glass rounded-xl sm:rounded-2xl p-3 sm:p-4 col-span-3 sm:col-span-1" style="background: linear-gradient(135deg, rgba(254,225,64,0.06), rgba(255,123,84,0.03)); border-color: rgba(254,225,64,0.2);">
                    <p class="text-[10px] sm:text-xs text-gray-400 mb-0.5">💰 Revenue</p>
                    <p class="text-base sm:text-lg font-black text-yellow-400">TZS {{ number_format($stats['total_cyber_revenue'] + $stats['total_food_revenue']) }}</p>
                </div>
            </div>

            {{-- TABS (scrollable on mobile) --}}
            <div class="flex gap-1.5 sm:gap-2 no-print overflow-x-auto pb-1 -mx-3 px-3 sm:mx-0 sm:px-0 scrollbar-none" id="tabs" style="scrollbar-width: none; -ms-overflow-style: none;">
                <button onclick="showTab('cyber')" id="tab-cyber" class="tab-active px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg sm:rounded-xl text-xs sm:text-sm font-semibold border transition-all whitespace-nowrap flex-shrink-0">
                    🍽️ Food ({{ $stats['total_cyber_orders'] }})
                </button>
                <button onclick="showTab('food')" id="tab-food" class="tab-inactive px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg sm:rounded-xl text-xs sm:text-sm font-semibold border transition-all whitespace-nowrap flex-shrink-0">
                    🛒 Sokoni ({{ $stats['total_food_orders'] }})
                </button>
                <button onclick="showTab('subs')" id="tab-subs" class="tab-inactive px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg sm:rounded-xl text-xs sm:text-sm font-semibold border transition-all whitespace-nowrap flex-shrink-0">
                    📦 Packages ({{ $stats['all_subscriptions'] }})
                </button>
                <button onclick="showTab('custom')" id="tab-custom" class="tab-inactive px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg sm:rounded-xl text-xs sm:text-sm font-semibold border transition-all whitespace-nowrap flex-shrink-0">
                    ✏️ Changes ({{ $stats['customizations_today'] }})
                </button>
                <button onclick="showTab('recent')" id="tab-recent" class="tab-inactive px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg sm:rounded-xl text-xs sm:text-sm font-semibold border transition-all whitespace-nowrap flex-shrink-0">
                    📋 7 Days ({{ $recentCyberOrders->count() + $recentFoodOrders->count() }})
                </button>
            </div>

            {{-- ═══ CYBER ORDERS BY MEAL SLOT ═══ --}}
            <div id="panel-cyber" class="space-y-3 sm:space-y-4">
                @php $hasCyberOrders = $cyberOrders->flatten()->count() > 0; @endphp

                @foreach($mealSlots as $slot)
                    @php $slotOrders = $cyberOrders->get($slot->id, collect()); @endphp
                    <div class="glass rounded-xl sm:rounded-2xl overflow-hidden">
                        {{-- Slot Header --}}
                        <div class="px-3 sm:px-5 py-3 sm:py-4 flex items-center justify-between gap-2" style="background: linear-gradient(135deg, rgba(0,255,200,0.06), transparent); border-bottom: 1px solid rgba(255,255,255,0.05);">
                            <div class="flex items-center gap-2 sm:gap-3 min-w-0">
                                <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-lg sm:rounded-xl flex items-center justify-center flex-shrink-0" style="background: linear-gradient(135deg, rgba(0,255,200,0.2), rgba(0,217,245,0.1));">
                                    @if($slot->isOpen())
                                        <span class="w-2.5 h-2.5 sm:w-3 sm:h-3 rounded-full bg-[#00ffc8] pulse-dot"></span>
                                    @else
                                        <span class="w-2.5 h-2.5 sm:w-3 sm:h-3 rounded-full bg-gray-500"></span>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <h3 class="font-bold text-white text-sm sm:text-lg truncate">{{ $slot->display_name }}</h3>
                                    <p class="text-[10px] sm:text-xs text-gray-400">{{ $slot->order_start_time->format('H:i') }} - {{ $slot->order_end_time->format('H:i') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-1.5 flex-shrink-0">
                                <span class="px-2 py-0.5 sm:px-3 sm:py-1 rounded-full text-[10px] sm:text-xs font-bold {{ $slot->isOpen() ? 'badge-active' : 'badge-cancelled' }}">
                                    {{ $slot->isOpen() ? '🟢 WAZI' : '🔴 CLOSED' }}
                                </span>
                                <span class="px-2 py-0.5 sm:px-3 sm:py-1 rounded-full text-[10px] sm:text-xs font-bold" style="background: rgba(139,92,246,0.15); color: #a78bfa; border: 1px solid rgba(139,92,246,0.3);">
                                    {{ $slotOrders->count() }}
                                </span>
                            </div>
                        </div>

                        @if($slotOrders->count() > 0)
                            {{-- Desktop Table --}}
                            <div class="desktop-table overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr style="background: rgba(139,92,246,0.06);">
                                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">#</th>
                                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Customer</th>
                                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Items</th>
                                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Total</th>
                                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</th>
                                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($slotOrders as $order)
                                            <tr class="order-row" style="border-bottom: 1px solid rgba(255,255,255,0.04);">
                                                <td class="px-5 py-3 font-mono text-xs text-gray-400">{{ $order->order_number }}</td>
                                                <td class="px-5 py-3">
                                                    <div class="font-semibold text-white text-sm">{{ $order->user?->name ?? 'Guest' }}</div>
                                                    <div class="text-xs text-gray-500">{{ $order->user?->phone }}</div>
                                                </td>
                                                <td class="px-5 py-3 text-xs text-gray-300">
                                                    @foreach($order->items as $item)
                                                        {{ $item->menuItem?->name ?? $item->item_name ?? 'Item' }} x{{ $item->quantity }}@if(!$loop->last), @endif
                                                    @endforeach
                                                </td>
                                                <td class="px-5 py-3 font-bold text-[#00ffc8] text-sm">TZS {{ number_format($order->total_amount) }}</td>
                                                <td class="px-5 py-3">
                                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold badge-{{ $order->status }}">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span>
                                                </td>
                                                <td class="px-5 py-3 text-xs text-gray-400">{{ $order->created_at->format('H:i') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{-- Mobile Cards --}}
                            <div class="mobile-card divide-y divide-white/5">
                                @foreach($slotOrders as $order)
                                    <div class="px-3 py-3 space-y-1.5">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <span class="font-semibold text-white text-sm">{{ $order->user?->name ?? 'Guest' }}</span>
                                                <span class="text-gray-500 text-[10px] ml-1">{{ $order->user?->phone }}</span>
                                            </div>
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold badge-{{ $order->status }}">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <div class="text-xs text-gray-300">
                                                @foreach($order->items as $item)
                                                    {{ $item->menuItem?->name ?? $item->item_name ?? 'Item' }} x{{ $item->quantity }}@if(!$loop->last), @endif
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="font-bold text-[#00ffc8] text-sm">TZS {{ number_format($order->total_amount) }}</span>
                                            <span class="text-[10px] text-gray-500 font-mono">{{ $order->order_number }} · {{ $order->created_at->format('H:i') }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="px-4 py-5 text-center">
                                <p class="text-gray-500 text-xs sm:text-sm">Hakuna order kwa {{ $slot->display_name }} tarehe hii</p>
                            </div>
                        @endif
                    </div>
                @endforeach

                @if(!$hasCyberOrders)
                    <div class="glass rounded-xl sm:rounded-2xl p-6 sm:p-8 text-center">
                        <div class="text-3xl sm:text-4xl mb-2">📭</div>
                        <p class="text-gray-400 text-sm sm:text-lg font-semibold">Hakuna Cyber orders tarehe {{ \Carbon\Carbon::parse($todayStr)->format('d M Y') }}</p>
                        <p class="text-gray-500 text-xs sm:text-sm mt-1">Jumla: <span class="text-[#00ffc8] font-bold">{{ $stats['all_cyber_orders'] }}</span> · Bonyeza <strong>"7 Days"</strong> kuona orders za hivi karibuni</p>
                    </div>
                @endif
            </div>

            {{-- ═══ FOOD/SOKONI ORDERS ═══ --}}
            <div id="panel-food" class="hidden space-y-3 sm:space-y-4">
                <div class="glass rounded-xl sm:rounded-2xl overflow-hidden">
                    <div class="px-3 sm:px-5 py-3 sm:py-4 flex items-center gap-2 sm:gap-3" style="background: linear-gradient(135deg, rgba(255,123,84,0.06), transparent); border-bottom: 1px solid rgba(255,255,255,0.05);">
                        <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-lg sm:rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, rgba(255,123,84,0.2), rgba(254,225,64,0.1));">
                            <span class="text-sm sm:text-base">🛒</span>
                        </div>
                        <div>
                            <h3 class="font-bold text-white text-sm sm:text-lg">Sokoni — {{ \Carbon\Carbon::parse($todayStr)->format('d M Y') }}</h3>
                            <p class="text-[10px] sm:text-xs text-gray-400">{{ $foodOrders->count() }} orders | {{ $stats['all_food_orders'] }} jumla</p>
                        </div>
                    </div>

                    @if($foodOrders->count() > 0)
                        <div class="desktop-table overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr style="background: rgba(255,123,84,0.05);">
                                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">#</th>
                                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Customer</th>
                                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Items</th>
                                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Total</th>
                                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($foodOrders as $order)
                                        <tr class="order-row" style="border-bottom: 1px solid rgba(255,255,255,0.04);">
                                            <td class="px-5 py-3 font-mono text-xs text-gray-400">{{ $order->order_number }}</td>
                                            <td class="px-5 py-3">
                                                <div class="font-semibold text-white text-sm">{{ $order->user?->name ?? 'Guest' }}</div>
                                                <div class="text-xs text-gray-500">{{ $order->user?->phone }}</div>
                                            </td>
                                            <td class="px-5 py-3 text-xs text-gray-300">
                                                @foreach($order->items as $item)
                                                    {{ $item->product_name }} x{{ $item->quantity }}{{ $item->unit }}@if(!$loop->last), @endif
                                                @endforeach
                                            </td>
                                            <td class="px-5 py-3 font-bold text-[#ff7b54] text-sm">TZS {{ number_format($order->total_amount) }}</td>
                                            <td class="px-5 py-3">
                                                <span class="px-2.5 py-1 rounded-full text-xs font-bold badge-{{ $order->status }}">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mobile-card divide-y divide-white/5">
                            @foreach($foodOrders as $order)
                                <div class="px-3 py-3 space-y-1.5">
                                    <div class="flex items-center justify-between">
                                        <span class="font-semibold text-white text-sm">{{ $order->user?->name ?? 'Guest' }}</span>
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold badge-{{ $order->status }}">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span>
                                    </div>
                                    <div class="text-xs text-gray-300">
                                        @foreach($order->items as $item)
                                            {{ $item->product_name }} x{{ $item->quantity }}{{ $item->unit }}@if(!$loop->last), @endif
                                        @endforeach
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="font-bold text-[#ff7b54] text-sm">TZS {{ number_format($order->total_amount) }}</span>
                                        <span class="text-[10px] text-gray-500 font-mono">{{ $order->order_number }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="px-4 py-6 text-center">
                            <div class="text-3xl mb-2">📭</div>
                            <p class="text-gray-400 text-sm font-semibold">Hakuna Sokoni orders tarehe hii</p>
                            <p class="text-gray-500 text-xs mt-1">Jumla: <span class="text-[#ff7b54] font-bold">{{ $stats['all_food_orders'] }}</span></p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- ═══ ALL SUBSCRIPTIONS ═══ --}}
            <div id="panel-subs" class="hidden space-y-3 sm:space-y-4">
                <div class="glass rounded-xl sm:rounded-2xl overflow-hidden">
                    <div class="px-3 sm:px-5 py-3 sm:py-4 flex items-center gap-2 sm:gap-3" style="background: linear-gradient(135deg, rgba(16,185,129,0.06), transparent); border-bottom: 1px solid rgba(255,255,255,0.05);">
                        <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-lg sm:rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, rgba(16,185,129,0.2), rgba(6,182,212,0.1));">
                            <span class="text-sm sm:text-base">📦</span>
                        </div>
                        <div>
                            <h3 class="font-bold text-white text-sm sm:text-lg">Vifurushi Vyote</h3>
                            <p class="text-[10px] sm:text-xs text-gray-400">
                                {{ $stats['active_subscriptions'] }} active · {{ $stats['paused_subscriptions'] }} paused · {{ $stats['all_subscriptions'] }} jumla
                            </p>
                        </div>
                    </div>

                    @if($subscriptions->count() > 0)
                        <div class="desktop-table overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr style="background: rgba(16,185,129,0.05);">
                                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Customer</th>
                                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Package</th>
                                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Items</th>
                                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Period</th>
                                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Days</th>
                                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($subscriptions as $sub)
                                        @php $daysLeft = $sub->end_date ? max(0, (int) now()->diffInDays($sub->end_date, false)) : 0; @endphp
                                        <tr class="order-row" style="border-bottom: 1px solid rgba(255,255,255,0.04);">
                                            <td class="px-5 py-3">
                                                <div class="font-semibold text-white text-sm">{{ $sub->user?->name }}</div>
                                                <div class="text-xs text-gray-500">{{ $sub->user?->phone }}</div>
                                                @if($sub->delivery_address)
                                                    <div class="text-xs text-gray-600 mt-0.5">📍 {{ Str::limit($sub->delivery_address, 30) }}</div>
                                                @endif
                                            </td>
                                            <td class="px-5 py-3 font-semibold text-emerald-400 text-sm">{{ $sub->package?->name ?? '—' }}</td>
                                            <td class="px-5 py-3 text-xs text-gray-300">
                                                @forelse($sub->package?->items ?? [] as $item)
                                                    <div class="flex items-center gap-1 mb-0.5">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 inline-block"></span>
                                                        {{ $item->product?->name ?? '?' }} — {{ $item->default_quantity }} {{ $item->product?->unit ?? '' }}
                                                    </div>
                                                @empty
                                                    <span class="text-gray-500">—</span>
                                                @endforelse
                                            </td>
                                            <td class="px-5 py-3 text-xs text-gray-400">
                                                @if($sub->start_date && $sub->end_date)
                                                    {{ $sub->start_date->format('d/m') }} → {{ $sub->end_date->format('d/m/Y') }}
                                                @else —
                                                @endif
                                            </td>
                                            <td class="px-5 py-3">
                                                @if(in_array($sub->status, ['active','paused']))
                                                    <span class="text-lg font-black {{ $daysLeft <= 3 ? 'text-red-400' : 'text-emerald-400' }}">{{ $daysLeft }}</span>
                                                    @if($daysLeft <= 3 && $daysLeft > 0)
                                                        <span class="text-[10px] text-red-400">⚠️</span>
                                                    @elseif($daysLeft === 0)
                                                        <span class="text-[10px] text-red-400">🔴</span>
                                                    @endif
                                                @else —
                                                @endif
                                            </td>
                                            <td class="px-5 py-3">
                                                <span class="px-2.5 py-1 rounded-full text-xs font-bold badge-{{ $sub->status }}">{{ ucfirst($sub->status) }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Mobile Cards --}}
                        <div class="mobile-card divide-y divide-white/5">
                            @foreach($subscriptions as $sub)
                                @php $daysLeft = $sub->end_date ? max(0, (int) now()->diffInDays($sub->end_date, false)) : 0; @endphp
                                <div class="px-3 py-3 space-y-2">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <span class="font-semibold text-white text-sm">{{ $sub->user?->name }}</span>
                                            <span class="text-gray-500 text-[10px] ml-1">{{ $sub->user?->phone }}</span>
                                        </div>
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold badge-{{ $sub->status }}">{{ ucfirst($sub->status) }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-emerald-400 font-semibold text-xs">{{ $sub->package?->name ?? '—' }}</span>
                                        @if(in_array($sub->status, ['active','paused']))
                                            <span class="text-sm font-black {{ $daysLeft <= 3 ? 'text-red-400' : 'text-emerald-400' }}">{{ $daysLeft }} siku</span>
                                        @endif
                                    </div>
                                    <div class="text-xs text-gray-400 space-y-0.5">
                                        @forelse($sub->package?->items ?? [] as $item)
                                            <div>• {{ $item->product?->name ?? '?' }} — {{ $item->default_quantity }} {{ $item->product?->unit ?? '' }}</div>
                                        @empty
                                            <div>—</div>
                                        @endforelse
                                    </div>
                                    @if($sub->start_date && $sub->end_date)
                                        <div class="text-[10px] text-gray-500">📅 {{ $sub->start_date->format('d/m') }} → {{ $sub->end_date->format('d/m/Y') }}</div>
                                    @endif
                                    @if($sub->delivery_address)
                                        <div class="text-[10px] text-gray-500">📍 {{ Str::limit($sub->delivery_address, 40) }}</div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="px-4 py-6 text-center">
                            <div class="text-3xl mb-2">📭</div>
                            <p class="text-gray-400 text-sm font-semibold">Hakuna vifurushi bado</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- ═══ CUSTOMIZATIONS ═══ --}}
            <div id="panel-custom" class="hidden space-y-3 sm:space-y-4">
                <div class="glass rounded-xl sm:rounded-2xl overflow-hidden">
                    <div class="px-3 sm:px-5 py-3 sm:py-4 flex items-center gap-2 sm:gap-3" style="background: linear-gradient(135deg, rgba(59,130,246,0.06), transparent); border-bottom: 1px solid rgba(255,255,255,0.05);">
                        <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-lg sm:rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, rgba(59,130,246,0.2), rgba(59,130,246,0.1));">
                            <span class="text-sm sm:text-base">✏️</span>
                        </div>
                        <div>
                            <h3 class="font-bold text-white text-sm sm:text-lg">Customizations — {{ \Carbon\Carbon::parse($todayStr)->format('d M') }}</h3>
                            <p class="text-[10px] sm:text-xs text-gray-400">{{ $customizations->count() }} mabadiliko</p>
                        </div>
                    </div>

                    @if($customizations->count() > 0)
                        <div class="desktop-table overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr style="background: rgba(59,130,246,0.05);">
                                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Customer</th>
                                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Package</th>
                                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Action</th>
                                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Details</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($customizations as $c)
                                        <tr class="order-row" style="border-bottom: 1px solid rgba(255,255,255,0.04);">
                                            <td class="px-5 py-3">
                                                <div class="font-semibold text-white text-sm">{{ $c->subscription?->user?->name }}</div>
                                                <div class="text-xs text-gray-500">{{ $c->subscription?->user?->phone }}</div>
                                            </td>
                                            <td class="px-5 py-3 text-sm text-gray-300">{{ $c->subscription?->package?->name }}</td>
                                            <td class="px-5 py-3">
                                                <span class="px-2.5 py-1 rounded-full text-xs font-bold badge-{{ $c->action_type }}">
                                                    @switch($c->action_type)
                                                        @case('swap') 🔄 Swap @break
                                                        @case('remove') 🗑️ Remove @break
                                                        @case('pause') ⏸️ Pause @break
                                                        @case('add') ➕ Add @break
                                                        @default {{ ucfirst($c->action_type) }}
                                                    @endswitch
                                                </span>
                                            </td>
                                            <td class="px-5 py-3 text-xs text-gray-300">
                                                @if($c->action_type === 'swap')
                                                    <span class="text-red-400">{{ $c->originalProduct?->name }}</span> → <span class="text-emerald-400">{{ $c->newProduct?->name }}</span>
                                                @elseif($c->action_type === 'remove')
                                                    <span class="text-red-400">Ondoa: {{ $c->originalProduct?->name }}</span>
                                                @elseif($c->action_type === 'pause')
                                                    <span class="text-yellow-400">Simamisha delivery</span>
                                                @else
                                                    {{ $c->originalProduct?->name ?? '—' }}
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mobile-card divide-y divide-white/5">
                            @foreach($customizations as $c)
                                <div class="px-3 py-3 space-y-1.5">
                                    <div class="flex items-center justify-between">
                                        <span class="font-semibold text-white text-sm">{{ $c->subscription?->user?->name }}</span>
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold badge-{{ $c->action_type }}">
                                            @switch($c->action_type)
                                                @case('swap') 🔄 Swap @break
                                                @case('remove') 🗑️ Remove @break
                                                @case('pause') ⏸️ Pause @break
                                                @case('add') ➕ Add @break
                                                @default {{ ucfirst($c->action_type) }}
                                            @endswitch
                                        </span>
                                    </div>
                                    <div class="text-xs text-gray-400">{{ $c->subscription?->package?->name }}</div>
                                    <div class="text-xs">
                                        @if($c->action_type === 'swap')
                                            <span class="text-red-400">{{ $c->originalProduct?->name }}</span> → <span class="text-emerald-400">{{ $c->newProduct?->name }}</span>
                                        @elseif($c->action_type === 'remove')
                                            <span class="text-red-400">Ondoa: {{ $c->originalProduct?->name }}</span>
                                        @elseif($c->action_type === 'pause')
                                            <span class="text-yellow-400">Simamisha delivery</span>
                                        @else
                                            {{ $c->originalProduct?->name ?? '—' }}
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="px-4 py-6 text-center">
                            <div class="text-3xl mb-2">✨</div>
                            <p class="text-gray-400 text-sm font-semibold">Hakuna mabadiliko tarehe hii</p>
                            @if($allCustomizations->count() > 0)
                                <p class="text-gray-500 text-xs mt-1">{{ $allCustomizations->count() }} mabadiliko ndani ya siku 7</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            {{-- ═══ RECENT 7 DAYS ═══ --}}
            <div id="panel-recent" class="hidden space-y-3 sm:space-y-4">

                {{-- Recent Cyber --}}
                <div class="glass rounded-xl sm:rounded-2xl overflow-hidden">
                    <div class="px-3 sm:px-5 py-3 sm:py-4 flex items-center gap-2 sm:gap-3" style="background: linear-gradient(135deg, rgba(0,255,200,0.06), transparent); border-bottom: 1px solid rgba(255,255,255,0.05);">
                        <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-lg sm:rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, rgba(0,255,200,0.2), rgba(0,217,245,0.1));">
                            <span class="text-sm sm:text-base">🍽️</span>
                        </div>
                        <div>
                            <h3 class="font-bold text-white text-sm sm:text-lg">Cyber — Siku 7</h3>
                            <p class="text-[10px] sm:text-xs text-gray-400">{{ $recentCyberOrders->count() }} orders</p>
                        </div>
                    </div>

                    @if($recentCyberOrders->count() > 0)
                        <div class="desktop-table overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr style="background: rgba(0,255,200,0.04);">
                                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">#</th>
                                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Customer</th>
                                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Slot</th>
                                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Items</th>
                                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Total</th>
                                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</th>
                                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentCyberOrders as $order)
                                        <tr class="order-row" style="border-bottom: 1px solid rgba(255,255,255,0.04);">
                                            <td class="px-5 py-3 font-mono text-xs text-gray-400">{{ $order->order_number }}</td>
                                            <td class="px-5 py-3">
                                                <div class="font-semibold text-white text-sm">{{ $order->user?->name ?? 'Guest' }}</div>
                                                <div class="text-xs text-gray-500">{{ $order->user?->phone }}</div>
                                            </td>
                                            <td class="px-5 py-3 text-xs text-gray-300">{{ $order->mealSlot?->display_name ?? '—' }}</td>
                                            <td class="px-5 py-3 text-xs text-gray-300">
                                                @foreach($order->items as $item)
                                                    {{ $item->menuItem?->name ?? $item->item_name ?? 'Item' }} x{{ $item->quantity }}@if(!$loop->last), @endif
                                                @endforeach
                                            </td>
                                            <td class="px-5 py-3 font-bold text-[#00ffc8] text-sm">TZS {{ number_format($order->total_amount) }}</td>
                                            <td class="px-5 py-3">
                                                <span class="px-2.5 py-1 rounded-full text-xs font-bold badge-{{ $order->status }}">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span>
                                            </td>
                                            <td class="px-5 py-3 text-xs text-gray-400">{{ $order->created_at->format('d/m H:i') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mobile-card divide-y divide-white/5">
                            @foreach($recentCyberOrders as $order)
                                <div class="px-3 py-3 space-y-1.5">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <span class="font-semibold text-white text-sm">{{ $order->user?->name ?? 'Guest' }}</span>
                                            <span class="text-gray-500 text-[10px] ml-1">{{ $order->user?->phone }}</span>
                                        </div>
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold badge-{{ $order->status }}">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-[10px] text-gray-400">{{ $order->mealSlot?->display_name ?? '—' }}</span>
                                        <span class="text-[10px] text-gray-500">{{ $order->created_at->format('d/m H:i') }}</span>
                                    </div>
                                    <div class="text-xs text-gray-300">
                                        @foreach($order->items as $item)
                                            {{ $item->menuItem?->name ?? $item->item_name ?? 'Item' }} x{{ $item->quantity }}@if(!$loop->last), @endif
                                        @endforeach
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="font-bold text-[#00ffc8] text-sm">TZS {{ number_format($order->total_amount) }}</span>
                                        <span class="text-[10px] text-gray-500 font-mono">{{ $order->order_number }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="px-4 py-6 text-center">
                            <p class="text-gray-500 text-sm">Hakuna cyber orders ndani ya siku 7</p>
                        </div>
                    @endif
                </div>

                {{-- Recent Food --}}
                <div class="glass rounded-xl sm:rounded-2xl overflow-hidden">
                    <div class="px-3 sm:px-5 py-3 sm:py-4 flex items-center gap-2 sm:gap-3" style="background: linear-gradient(135deg, rgba(255,123,84,0.06), transparent); border-bottom: 1px solid rgba(255,255,255,0.05);">
                        <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-lg sm:rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, rgba(255,123,84,0.2), rgba(254,225,64,0.1));">
                            <span class="text-sm sm:text-base">🛒</span>
                        </div>
                        <div>
                            <h3 class="font-bold text-white text-sm sm:text-lg">Sokoni — Siku 7</h3>
                            <p class="text-[10px] sm:text-xs text-gray-400">{{ $recentFoodOrders->count() }} orders</p>
                        </div>
                    </div>

                    @if($recentFoodOrders->count() > 0)
                        <div class="desktop-table overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr style="background: rgba(255,123,84,0.04);">
                                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">#</th>
                                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Customer</th>
                                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Items</th>
                                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Total</th>
                                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</th>
                                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentFoodOrders as $order)
                                        <tr class="order-row" style="border-bottom: 1px solid rgba(255,255,255,0.04);">
                                            <td class="px-5 py-3 font-mono text-xs text-gray-400">{{ $order->order_number }}</td>
                                            <td class="px-5 py-3">
                                                <div class="font-semibold text-white text-sm">{{ $order->user?->name ?? 'Guest' }}</div>
                                                <div class="text-xs text-gray-500">{{ $order->user?->phone }}</div>
                                            </td>
                                            <td class="px-5 py-3 text-xs text-gray-300">
                                                @foreach($order->items as $item)
                                                    {{ $item->product_name }} x{{ $item->quantity }}{{ $item->unit }}@if(!$loop->last), @endif
                                                @endforeach
                                            </td>
                                            <td class="px-5 py-3 font-bold text-[#ff7b54] text-sm">TZS {{ number_format($order->total_amount) }}</td>
                                            <td class="px-5 py-3">
                                                <span class="px-2.5 py-1 rounded-full text-xs font-bold badge-{{ $order->status }}">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span>
                                            </td>
                                            <td class="px-5 py-3 text-xs text-gray-400">{{ $order->created_at->format('d/m H:i') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mobile-card divide-y divide-white/5">
                            @foreach($recentFoodOrders as $order)
                                <div class="px-3 py-3 space-y-1.5">
                                    <div class="flex items-center justify-between">
                                        <span class="font-semibold text-white text-sm">{{ $order->user?->name ?? 'Guest' }}</span>
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold badge-{{ $order->status }}">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span>
                                    </div>
                                    <div class="text-xs text-gray-300">
                                        @foreach($order->items as $item)
                                            {{ $item->product_name }} x{{ $item->quantity }}{{ $item->unit }}@if(!$loop->last), @endif
                                        @endforeach
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="font-bold text-[#ff7b54] text-sm">TZS {{ number_format($order->total_amount) }}</span>
                                        <span class="text-[10px] text-gray-500">{{ $order->order_number }} · {{ $order->created_at->format('d/m H:i') }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="px-4 py-6 text-center">
                            <p class="text-gray-500 text-sm">Hakuna sokoni orders ndani ya siku 7</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <script>
        function showTab(name) {
            const panels = ['cyber', 'food', 'subs', 'custom', 'recent'];
            panels.forEach(p => {
                document.getElementById('panel-' + p).classList.toggle('hidden', p !== name);
                const tab = document.getElementById('tab-' + p);
                tab.classList.toggle('tab-active', p === name);
                tab.classList.toggle('tab-inactive', p !== name);
            });
        }
        setTimeout(() => location.reload(), 120000);
    </script>
</body>
</html>
