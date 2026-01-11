<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin - {{ config('app.name', 'SmartFood Hub') }} - @yield('title', 'Dashboard')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Global Scrollbar Styles - Load First -->
    <style>
        /* FORCEFUL Global colorful scrollbar - Must be at top level */
        * {
            scrollbar-width: thin !important;
            scrollbar-color: #f97316 #f0f0f0 !important;
        }

        *::-webkit-scrollbar {
            width: 14px !important;
            height: 14px !important;
        }

        *::-webkit-scrollbar-track {
            background: linear-gradient(to bottom, #fff7ed, #ffedd5, #fed7aa) !important;
            border-radius: 10px !important;
        }

        *::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #f97316 0%, #ea580c 25%, #dc2626 50%, #b91c1c 75%, #991b1b 100%) !important;
            border-radius: 10px !important;
            border: 2px solid #fed7aa !important;
            box-shadow: 0 0 10px rgba(249, 115, 22, 0.5) !important;
        }

        *::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #ea580c 0%, #dc2626 25%, #b91c1c 50%, #991b1b 75%, #7f1d1d 100%) !important;
            box-shadow: 0 0 15px rgba(249, 115, 22, 0.8) !important;
        }

        .dark *::-webkit-scrollbar-track {
            background: linear-gradient(to bottom, #1e1b4b, #312e81, #3730a3) !important;
        }

        .dark *::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #a855f7 0%, #9333ea 25%, #7c3aed 50%, #6366f1 75%, #4f46e5 100%) !important;
            border: 2px solid rgba(99, 102, 241, 0.5) !important;
        }

        .dark *::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #9333ea 0%, #7c3aed 25%, #6366f1 50%, #4f46e5 75%, #4338ca 100%) !important;
        }
    </style>

    <style>
        @keyframes slide-in {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        .animate-slide-in {
            animation: slide-in 0.3s ease-out;
        }
        .sidebar-collapsed {
            width: 80px;
        }
        .sidebar-collapsed .menu-text,
        .sidebar-collapsed .menu-badge {
            display: none;
        }
        /* Sidebar toggle button - visible on mobile only */
        #sidebarToggle {
            display: flex !important;
            visibility: visible !important;
            opacity: 1 !important;
        }
        
        @media (min-width: 1024px) {
            #sidebarToggle {
                display: none !important;
            }
        }
        
        /* Ensure sidebar has proper flex structure for scrolling */
        #sidebar {
            display: flex !important;
            flex-direction: column !important;
            overflow: hidden !important;
        }
        
        /* Make navigation menu scrollable */
        #sidebar nav {
            flex: 1 1 auto !important;
            overflow-y: auto !important;
            overflow-x: hidden !important;
            min-height: 0 !important;
        }
        .sidebar-collapsed .menu-icon {
            margin-right: 0;
        }
        .sidebar-collapsed .submenu {
            display: none;
        }
        .submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }
        .submenu.open {
            max-height: 500px;
            transition: max-height 0.5s ease-in;
        }
        @media (max-width: 1024px) {
            #mainContent {
                margin-left: 0 !important;
            }
        }
        
        /* Fix sidebar and main content positioning */
        #sidebar {
            position: fixed !important;
            left: 0 !important;
            top: 0 !important;
            z-index: 50 !important;
            height: 100vh !important;
            width: 18rem !important; /* w-72 = 18rem */
            transition: transform 0.3s ease-in-out !important;
        }
        
        /* Hide sidebar on mobile by default */
        #sidebar.-translate-x-full {
            transform: translateX(-100%) !important;
        }
        
        /* Always show sidebar on large screens - override any hidden state */
        @media (min-width: 1024px) {
            #sidebar {
                transform: translateX(0) !important;
            }
            #sidebar.-translate-x-full {
                transform: translateX(0) !important;
            }
        }
        
        #mainContent {
            position: relative !important;
            z-index: 10 !important;
            transition: margin-left 0.3s ease-in-out !important;
        }
        
        @media (min-width: 1024px) {
            #mainContent.lg\:ml-72 {
                margin-left: 18rem !important; /* lg:ml-72 = 18rem */
            }
        }
        
        @media (max-width: 1023px) {
            #mainContent {
                margin-left: 0 !important;
            }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-50 via-blue-50 to-slate-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 min-h-screen">
    <!-- Sidebar -->
    <aside id="sidebar" class="fixed left-0 top-0 z-50 h-screen w-72 bg-gradient-to-b from-purple-900 via-indigo-900 via-blue-900 to-cyan-900 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 text-white shadow-2xl transition-all duration-300 ease-in-out lg:translate-x-0 -translate-x-full flex flex-col">
        <!-- Animated background overlay -->
        <div class="absolute inset-0 bg-gradient-to-br from-orange-500/10 via-pink-500/10 via-purple-500/10 to-blue-500/10 animate-pulse"></div>
        <!-- Sidebar Header -->
        <div class="flex items-center justify-between p-6 border-b border-purple-500/30 dark:border-slate-700 bg-gradient-to-r from-purple-600/30 via-indigo-600/30 to-blue-600/30 backdrop-blur-sm relative z-10">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-xl flex items-center justify-center shadow-lg transform hover:scale-110 transition-transform">
                    <span class="text-2xl">🍽️</span>
                </div>
                <div class="menu-text">
                    <h1 class="text-xl font-bold text-white">SmartFood</h1>
                    <p class="text-xs text-blue-300">Admin Panel</p>
                </div>
            </div>
            <!-- Toggle button - hidden on large screens, visible on mobile -->
            <button id="sidebarToggle" class="lg:hidden p-2 rounded-lg hover:bg-purple-700/50 dark:hover:bg-slate-700 transition-colors flex-shrink-0 z-20 relative" type="button" aria-label="Toggle sidebar">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- User Profile -->
        <div class="p-6 border-b border-purple-500/30 dark:border-slate-700 bg-gradient-to-r from-pink-600/20 via-purple-600/20 to-indigo-600/20 backdrop-blur-sm relative z-10">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-lg">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div class="menu-text flex-1 min-w-0">
                    <p class="font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-blue-300 truncate">{{ auth()->user()->email }}</p>
                </div>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="flex-1 overflow-y-auto py-4 px-3 custom-scrollbar relative z-10">
            <ul class="space-y-2">
                <!-- Dashboard -->
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-300 hover:scale-105 {{ request()->routeIs('admin.dashboard') ? 'bg-gradient-to-r from-orange-500 via-pink-500 to-purple-600 shadow-xl shadow-purple-500/50' : 'hover:bg-gradient-to-r hover:from-orange-500/30 hover:via-pink-500/30 hover:to-purple-600/30 hover:backdrop-blur-sm' }}">
                        <div class="menu-icon w-6 h-6 flex items-center justify-center {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-purple-300' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                        </div>
                        <span class="menu-text flex-1 font-medium {{ request()->routeIs('admin.dashboard') ? 'text-white font-bold' : 'text-purple-100' }}">Dashboard</span>
                    </a>
                </li>

                <!-- Orders -->
                <li>
                    <a href="{{ route('admin.orders.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-300 hover:scale-105 {{ request()->routeIs('admin.orders.*') ? 'bg-gradient-to-r from-yellow-500 via-orange-500 to-red-500 shadow-xl shadow-orange-500/50' : 'hover:bg-gradient-to-r hover:from-yellow-500/30 hover:via-orange-500/30 hover:to-red-500/30 hover:backdrop-blur-sm' }}">
                        <div class="menu-icon w-6 h-6 flex items-center justify-center {{ request()->routeIs('admin.orders.*') ? 'text-white' : 'text-yellow-300' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                        </div>
                        <span class="menu-text flex-1 font-medium {{ request()->routeIs('admin.orders.*') ? 'text-white font-bold' : 'text-yellow-100' }}">Orders</span>
                        <span class="menu-badge px-2 py-1 text-xs font-bold bg-gradient-to-r from-red-500 to-pink-500 text-white rounded-full shadow-lg">{{ \App\Models\Order::where('status', 'pending')->count() }}</span>
                    </a>
                </li>

                <!-- Subscriptions -->
                <li>
                    <a href="{{ route('admin.subscriptions.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-300 hover:scale-105 {{ request()->routeIs('admin.subscriptions.*') ? 'bg-gradient-to-r from-green-500 via-emerald-500 to-teal-500 shadow-xl shadow-emerald-500/50' : 'hover:bg-gradient-to-r hover:from-green-500/30 hover:via-emerald-500/30 hover:to-teal-500/30 hover:backdrop-blur-sm' }}">
                        <div class="menu-icon w-6 h-6 flex items-center justify-center {{ request()->routeIs('admin.subscriptions.*') ? 'text-white' : 'text-green-300' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <span class="menu-text flex-1 font-medium {{ request()->routeIs('admin.subscriptions.*') ? 'text-white font-bold' : 'text-green-100' }}">Subscriptions</span>
                    </a>
                </li>

                <!-- Menu Management (Collapsible) -->
                <li>
                    <button type="button" onclick="toggleSubmenu('menuSubmenu')" class="w-full flex items-center justify-between px-4 py-3 rounded-xl transition-all duration-300 hover:scale-105 {{ request()->routeIs('admin.menu.*') ? 'bg-gradient-to-r from-cyan-500 via-blue-500 to-indigo-500 shadow-xl shadow-blue-500/50' : 'hover:bg-gradient-to-r hover:from-cyan-500/30 hover:via-blue-500/30 hover:to-indigo-500/30 hover:backdrop-blur-sm' }}">
                        <div class="flex items-center space-x-3">
                            <div class="menu-icon w-6 h-6 flex items-center justify-center {{ request()->routeIs('admin.menu.*') ? 'text-white' : 'text-cyan-300' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                                </svg>
                            </div>
                            <span class="menu-text flex-1 text-left font-medium {{ request()->routeIs('admin.menu.*') ? 'text-white font-bold' : 'text-cyan-100' }}">Menu Management</span>
                        </div>
                        <svg id="menuSubmenuIcon" class="w-4 h-4 transition-transform duration-200 {{ request()->routeIs('admin.menu.*') ? 'text-white' : 'text-cyan-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <ul id="menuSubmenu" class="submenu mt-2 ml-4 space-y-1 {{ request()->routeIs('admin.menu.*') ? 'open' : '' }}">
                        <li>
                            <a href="{{ route('admin.menu.index') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm transition-all duration-300 hover:scale-105 {{ request()->routeIs('admin.menu.index') ? 'bg-gradient-to-r from-cyan-400 to-blue-500 shadow-lg' : 'hover:bg-gradient-to-r hover:from-cyan-400/30 hover:to-blue-500/30' }}">
                                <span class="w-2 h-2 rounded-full bg-gradient-to-r from-cyan-400 to-blue-500 shadow-sm"></span>
                                <span class="menu-text {{ request()->routeIs('admin.menu.index') ? 'text-white font-semibold' : 'text-cyan-200' }}">Overview</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.menu.categories.create') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm transition-all duration-300 hover:scale-105 {{ request()->routeIs('admin.menu.categories.*') ? 'bg-gradient-to-r from-orange-400 to-pink-500 shadow-lg' : 'hover:bg-gradient-to-r hover:from-orange-400/30 hover:to-pink-500/30' }}">
                                <span class="w-2 h-2 rounded-full bg-gradient-to-r from-orange-400 to-pink-500 shadow-sm"></span>
                                <span class="menu-text {{ request()->routeIs('admin.menu.categories.*') ? 'text-white font-semibold' : 'text-orange-200' }}">Categories</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.menu.food-items.create') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm transition-all duration-300 hover:scale-105 {{ request()->routeIs('admin.menu.food-items.*') ? 'bg-gradient-to-r from-yellow-400 to-orange-500 shadow-lg' : 'hover:bg-gradient-to-r hover:from-yellow-400/30 hover:to-orange-500/30' }}">
                                <span class="w-2 h-2 rounded-full bg-gradient-to-r from-yellow-400 to-orange-500 shadow-sm"></span>
                                <span class="menu-text {{ request()->routeIs('admin.menu.food-items.*') ? 'text-white font-semibold' : 'text-yellow-200' }}">Food Items</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.menu.kitchen-products.create') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm transition-all duration-300 hover:scale-105 {{ request()->routeIs('admin.menu.kitchen-products.*') ? 'bg-gradient-to-r from-teal-400 to-cyan-500 shadow-lg' : 'hover:bg-gradient-to-r hover:from-teal-400/30 hover:to-cyan-500/30' }}">
                                <span class="w-2 h-2 rounded-full bg-gradient-to-r from-teal-400 to-cyan-500 shadow-sm"></span>
                                <span class="menu-text {{ request()->routeIs('admin.menu.kitchen-products.*') ? 'text-white font-semibold' : 'text-teal-200' }}">Kitchen Products</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.menu.subscription-packages.create') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm transition-all duration-300 hover:scale-105 {{ request()->routeIs('admin.menu.subscription-packages.*') ? 'bg-gradient-to-r from-purple-400 to-pink-500 shadow-lg' : 'hover:bg-gradient-to-r hover:from-purple-400/30 hover:to-pink-500/30' }}">
                                <span class="w-2 h-2 rounded-full bg-gradient-to-r from-purple-400 to-pink-500 shadow-sm"></span>
                                <span class="menu-text {{ request()->routeIs('admin.menu.subscription-packages.*') ? 'text-white font-semibold' : 'text-purple-200' }}">Subscription Packages</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Settings -->
                <li>
                    <a href="{{ route('admin.settings.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-300 hover:scale-105 {{ request()->routeIs('admin.settings.*') ? 'bg-gradient-to-r from-slate-600 via-gray-600 to-slate-600 shadow-xl shadow-gray-500/50' : 'hover:bg-gradient-to-r hover:from-slate-600/30 hover:via-gray-600/30 hover:to-slate-600/30 hover:backdrop-blur-sm' }}">
                        <div class="menu-icon w-6 h-6 flex items-center justify-center {{ request()->routeIs('admin.settings.*') ? 'text-white' : 'text-slate-300' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <span class="menu-text flex-1 font-medium {{ request()->routeIs('admin.settings.*') ? 'text-white font-bold' : 'text-slate-200' }}">Settings</span>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Sidebar Footer -->
        <div class="p-4 border-t border-purple-500/30 dark:border-slate-700 bg-gradient-to-r from-indigo-600/30 via-purple-600/30 to-pink-600/30 backdrop-blur-sm relative z-10">
            <a href="{{ route('home') }}" target="_blank" class="flex items-center space-x-3 px-4 py-2 rounded-xl transition-all duration-300 hover:bg-gradient-to-r hover:from-blue-500/30 hover:to-cyan-500/30 hover:scale-105 text-sm menu-text text-cyan-200 hover:text-white">
                <div class="menu-icon w-5 h-5 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                    </svg>
                </div>
                <span class="menu-text">View Site</span>
            </a>
            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <button type="submit" class="w-full flex items-center space-x-3 px-4 py-2 rounded-xl transition-all duration-300 hover:bg-gradient-to-r hover:from-red-600 hover:to-pink-600 hover:scale-105 text-sm menu-text text-red-200 hover:text-white shadow-lg hover:shadow-red-500/50">
                    <div class="menu-icon w-5 h-5 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                    </div>
                    <span class="menu-text">Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <div id="mainContent" class="transition-all duration-300 ease-in-out">
        <!-- Top Bar -->
        <header class="sticky top-0 z-40 bg-white/80 dark:bg-slate-800/80 backdrop-blur-lg border-b border-slate-200 dark:border-slate-700 shadow-sm">
            <div class="flex items-center justify-between px-6 py-4">
                <div class="flex items-center space-x-4">
                    <!-- Mobile toggle (hamburger) - visible on small screens only -->
                    <button id="mobileSidebarToggle" class="lg:hidden p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    <div>
                        <h2 class="text-2xl font-bold text-slate-900 dark:text-white">@yield('title', 'Dashboard')</h2>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Welcome back, {{ auth()->user()->name }}! 👋</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="hidden md:flex items-center space-x-2 px-4 py-2 bg-blue-50 dark:bg-blue-900/30 rounded-lg">
                        <span class="text-sm font-medium text-blue-700 dark:text-blue-300">{{ now()->format('M d, Y') }}</span>
                        <span class="text-sm text-blue-600 dark:text-blue-400">{{ now()->format('h:i A') }}</span>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="p-6">
            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="mb-6 bg-green-50 dark:bg-green-900/20 border-l-4 border-green-500 p-4 rounded-lg shadow-sm animate-slide-in">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <p class="text-green-800 dark:text-green-200 font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 p-4 rounded-lg shadow-sm animate-slide-in">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                        <p class="text-red-800 dark:text-red-200 font-medium">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Custom Scrollbar Styles -->
    <style>
        /* Vibrant Colorful Scrollbar for sidebar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 10px !important;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: linear-gradient(to bottom, rgba(30, 27, 75, 0.8), rgba(49, 46, 129, 0.8)) !important;
            border-radius: 10px !important;
            box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.3) !important;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: linear-gradient(to bottom, #a855f7, #9333ea, #7c3aed, #6366f1) !important;
            border-radius: 10px !important;
            border: 2px solid rgba(99, 102, 241, 0.3) !important;
            box-shadow: 0 2px 6px rgba(168, 85, 247, 0.5) !important;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(to bottom, #9333ea, #7c3aed, #6366f1, #4f46e5) !important;
            box-shadow: 0 4px 8px rgba(168, 85, 247, 0.8) !important;
        }

        /* Global colorful scrollbar - MUST BE FORCEFUL */
        * {
            scrollbar-width: thin !important;
            scrollbar-color: #f97316 #f0f0f0 !important;
        }

        *::-webkit-scrollbar {
            width: 14px !important;
            height: 14px !important;
        }

        *::-webkit-scrollbar-track {
            background: linear-gradient(to bottom, #fff7ed, #ffedd5, #fed7aa) !important;
            border-radius: 10px !important;
        }

        *::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #f97316 0%, #ea580c 25%, #dc2626 50%, #b91c1c 75%, #991b1b 100%) !important;
            border-radius: 10px !important;
            border: 2px solid #fed7aa !important;
            box-shadow: 0 0 10px rgba(249, 115, 22, 0.5) !important;
        }

        *::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #ea580c 0%, #dc2626 25%, #b91c1c 50%, #991b1b 75%, #7f1d1d 100%) !important;
            box-shadow: 0 0 15px rgba(249, 115, 22, 0.8) !important;
        }

        *::-webkit-scrollbar-corner {
            background: linear-gradient(to bottom right, #fff7ed, #ffedd5) !important;
        }

        /* Body and HTML specific */
        html {
            scrollbar-width: thin !important;
            scrollbar-color: #f97316 #f0f0f0 !important;
        }

        html::-webkit-scrollbar {
            width: 14px !important;
        }

        html::-webkit-scrollbar-track {
            background: linear-gradient(to bottom, #fff7ed, #ffedd5, #fed7aa) !important;
            border-radius: 10px !important;
        }

        html::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #f97316 0%, #ea580c 25%, #dc2626 50%, #b91c1c 75%, #991b1b 100%) !important;
            border-radius: 10px !important;
            border: 2px solid #fed7aa !important;
            box-shadow: 0 0 10px rgba(249, 115, 22, 0.5) !important;
        }

        html::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #ea580c 0%, #dc2626 25%, #b91c1c 50%, #991b1b 75%, #7f1d1d 100%) !important;
            box-shadow: 0 0 15px rgba(249, 115, 22, 0.8) !important;
        }

        body {
            scrollbar-width: thin !important;
            scrollbar-color: #f97316 #ffffff !important;
        }

        body::-webkit-scrollbar {
            width: 14px !important;
        }

        body::-webkit-scrollbar-track {
            background: linear-gradient(to bottom, #fff7ed, #ffedd5, #fed7aa) !important;
            border-radius: 10px !important;
        }

        body::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #f97316 0%, #ea580c 25%, #dc2626 50%, #b91c1c 75%, #991b1b 100%) !important;
            border-radius: 10px !important;
            border: 2px solid #fed7aa !important;
            box-shadow: 0 0 10px rgba(249, 115, 22, 0.5) !important;
        }

        body::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #ea580c 0%, #dc2626 25%, #b91c1c 50%, #991b1b 75%, #7f1d1d 100%) !important;
            box-shadow: 0 0 15px rgba(249, 115, 22, 0.8) !important;
        }

        /* Dark mode scrollbar */
        .dark *::-webkit-scrollbar-track {
            background: linear-gradient(to bottom, #1e1b4b, #312e81, #3730a3) !important;
        }

        .dark *::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #a855f7 0%, #9333ea 25%, #7c3aed 50%, #6366f1 75%, #4f46e5 100%) !important;
            border: 2px solid rgba(99, 102, 241, 0.5) !important;
            box-shadow: 0 2px 6px rgba(168, 85, 247, 0.5) !important;
        }

        .dark *::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #9333ea 0%, #7c3aed 25%, #6366f1 50%, #4f46e5 75%, #4338ca 100%) !important;
            box-shadow: 0 4px 8px rgba(168, 85, 247, 0.8) !important;
        }
    </style>

    <script>
        // Sidebar Toggle
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const mobileSidebarToggle = document.getElementById('mobileSidebarToggle');
        let isHidden = false;

        // Ensure sidebar is visible on large screens on page load
        function ensureSidebarVisible() {
            if (window.innerWidth >= 1024) {
                sidebar.classList.remove('-translate-x-full');
                sidebar.classList.add('lg:translate-x-0');
                if (!mainContent.classList.contains('lg:ml-72')) {
                    mainContent.classList.add('lg:ml-72');
                }
                mainContent.style.marginLeft = '';
            }
        }

        // Run on page load
        ensureSidebarVisible();

        // Run on window resize
        window.addEventListener('resize', ensureSidebarVisible);

        // Mobile toggle (X button in sidebar) - Only works on small screens
        sidebarToggle?.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            // Only toggle on mobile screens
            if (window.innerWidth < 1024) {
                sidebar.classList.add('-translate-x-full');
            }
        });

        // Mobile toggle
        mobileSidebarToggle?.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            sidebar.classList.toggle('-translate-x-full');
            isHidden = sidebar.classList.contains('-translate-x-full');
        });

        // Close sidebar when clicking outside on mobile only
        document.addEventListener('click', (e) => {
            // Only handle on mobile screens
            if (window.innerWidth < 1024) {
                if (!sidebar.contains(e.target) && !mobileSidebarToggle.contains(e.target)) {
                    if (!sidebar.classList.contains('-translate-x-full')) {
                        sidebar.classList.add('-translate-x-full');
                        isHidden = true;
                    }
                }
            }
        });

        // Submenu toggle function
        function toggleSubmenu(submenuId) {
            const submenu = document.getElementById(submenuId);
            const icon = document.getElementById(submenuId + 'Icon');
            submenu.classList.toggle('open');
            icon.classList.toggle('rotate-180');
        }

        // Auto-collapse submenu if not on menu pages
        document.addEventListener('DOMContentLoaded', () => {
            const menuSubmenu = document.getElementById('menuSubmenu');
            if (!window.location.pathname.includes('/admin/menu')) {
                menuSubmenu?.classList.remove('open');
            }
        });
    </script>
</body>
</html>
