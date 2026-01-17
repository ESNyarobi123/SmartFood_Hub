<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Monana Platform') - Food Delivery & Kitchen Essentials</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --bg-primary: #0f0f0f;
            --bg-secondary: #1a1a1a;
            --bg-card: #242424;
            --accent-cyber: #00d4aa;
            --accent-food: #ff6b35;
        }

        * {
            scrollbar-width: thin;
            scrollbar-color: #404040 #1a1a1a;
        }

        *::-webkit-scrollbar {
            width: 8px;
        }

        *::-webkit-scrollbar-track {
            background: #1a1a1a;
        }

        *::-webkit-scrollbar-thumb {
            background: #404040;
            border-radius: 4px;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-primary);
            color: #ffffff;
        }

        body.home-page {
            background: #ffffff;
            color: #1a1a1a;
        }

        .card {
            background: var(--bg-card);
            border: 1px solid #333;
            border-radius: 12px;
        }

        .glow-cyber:hover {
            box-shadow: 0 0 30px rgba(0, 212, 170, 0.3);
        }

        .glow-food:hover {
            box-shadow: 0 0 30px rgba(255, 107, 53, 0.3);
        }
    </style>
</head>
<body class="min-h-screen {{ request()->routeIs('home') ? 'home-page' : '' }}">
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 {{ request()->routeIs('home') ? 'bg-white backdrop-blur-lg border-b border-gray-200' : 'bg-[#1a1a1a]/90 backdrop-blur-lg border-b border-[#333]' }}" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center h-16 w-full">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center space-x-2 sm:space-x-3 flex-shrink-0">
                    <img src="{{ asset('images/lg.jpeg') }}" alt="Monana Group Logo" class="h-8 sm:h-10 md:h-12 w-auto object-contain">
                </a>

                <!-- Desktop Navigation - Center -->
                <div class="hidden lg:flex items-center justify-center flex-1 space-x-6 xl:space-x-8 mx-8">
                    <a href="{{ route('cyber.index') }}" class="text-sm font-medium {{ request()->routeIs('home') ? 'text-gray-800 hover:text-red-600' : 'text-[#a0a0a0] hover:text-[#00d4aa]' }} transition-colors whitespace-nowrap">Monana Food</a>
                    <a href="{{ route('food.index') }}" class="text-sm font-medium {{ request()->routeIs('home') ? 'text-gray-800 hover:text-orange-500' : 'text-[#a0a0a0] hover:text-[#ff6b35]' }} transition-colors whitespace-nowrap">Monana Market</a>
                </div>
                
                <!-- Desktop Auth Section - Right -->
                <div class="hidden lg:flex items-center space-x-4 flex-shrink-0 ml-auto">
                    @auth
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center space-x-2 text-sm {{ request()->routeIs('home') ? 'text-gray-800 hover:text-gray-900' : 'text-[#a0a0a0] hover:text-white' }} transition-colors">
                                <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center">
                                    <span class="text-xs font-bold text-white">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</span>
                                </div>
                                <span class="whitespace-nowrap">{{ auth()->user()->name }}</span>
                            </button>
                            <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 {{ request()->routeIs('home') ? 'bg-white border border-gray-200' : 'bg-[#242424] border border-[#333]' }} rounded-lg shadow-xl z-50">
                                <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm {{ request()->routeIs('home') ? 'text-gray-700 hover:text-gray-900 hover:bg-gray-50' : 'text-[#a0a0a0] hover:text-white hover:bg-[#333]' }}">My Dashboard</a>
                                <a href="{{ route('food.dashboard') }}" class="block px-4 py-2 text-sm {{ request()->routeIs('home') ? 'text-gray-700 hover:text-gray-900 hover:bg-gray-50' : 'text-[#a0a0a0] hover:text-white hover:bg-[#333]' }}">My Subscriptions</a>
                                <a href="{{ route('cyber.orders') }}" class="block px-4 py-2 text-sm {{ request()->routeIs('home') ? 'text-gray-700 hover:text-gray-900 hover:bg-gray-50' : 'text-[#a0a0a0] hover:text-white hover:bg-[#333]' }}">Cyber Orders</a>
                                <a href="{{ route('food.orders') }}" class="block px-4 py-2 text-sm {{ request()->routeIs('home') ? 'text-gray-700 hover:text-gray-900 hover:bg-gray-50' : 'text-[#a0a0a0] hover:text-white hover:bg-[#333]' }}">Food Orders</a>
                                @if(auth()->user()->is_admin)
                                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm {{ request()->routeIs('home') ? 'text-indigo-600 hover:text-indigo-700 hover:bg-gray-50' : 'text-indigo-400 hover:text-indigo-300 hover:bg-[#333]' }}">Admin Panel</a>
                                @endif
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm {{ request()->routeIs('home') ? 'text-red-600 hover:text-red-700 hover:bg-gray-50' : 'text-red-400 hover:text-red-300 hover:bg-[#333]' }}">Logout</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-semibold {{ request()->routeIs('home') ? 'text-gray-900 hover:text-gray-700' : 'text-white hover:text-[#a0a0a0]' }} transition-colors px-3 py-2 whitespace-nowrap">Login</a>
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-5 py-2.5 {{ request()->routeIs('home') ? 'bg-gray-900 text-white border-2 border-gray-900 hover:bg-gray-800' : 'bg-white text-gray-900 border-2 border-gray-900 hover:bg-gray-100' }} text-sm font-bold rounded-lg transition-all duration-300 whitespace-nowrap">Register</a>
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden p-2 {{ request()->routeIs('home') ? 'text-gray-900' : 'text-white' }}" aria-label="Toggle menu">
                    <svg x-show="!mobileMenuOpen" x-transition class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <svg x-show="mobileMenuOpen" x-transition class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Mobile Menu -->
            <div x-show="mobileMenuOpen" x-transition class="lg:hidden pb-4 border-t {{ request()->routeIs('home') ? 'border-gray-200' : 'border-[#333]' }}">
                <div class="flex flex-col space-y-4 mt-4">
                    <a href="{{ route('cyber.index') }}" class="text-sm font-medium {{ request()->routeIs('home') ? 'text-gray-700 hover:text-red-600' : 'text-[#a0a0a0] hover:text-[#00d4aa]' }} transition-colors px-2 py-2">Monana Food</a>
                    <a href="{{ route('food.index') }}" class="text-sm font-medium {{ request()->routeIs('home') ? 'text-gray-700 hover:text-orange-500' : 'text-[#a0a0a0] hover:text-[#ff6b35]' }} transition-colors px-2 py-2">Monana Market</a>
                    @auth
                        <div class="border-t {{ request()->routeIs('home') ? 'border-gray-200' : 'border-[#333]' }} pt-4">
                            <a href="{{ route('dashboard') }}" class="block px-2 py-2 text-sm {{ request()->routeIs('home') ? 'text-gray-700 hover:text-gray-900 hover:bg-gray-50' : 'text-[#a0a0a0] hover:text-white hover:bg-[#333]' }} rounded-lg">My Dashboard</a>
                            <a href="{{ route('food.dashboard') }}" class="block px-2 py-2 text-sm {{ request()->routeIs('home') ? 'text-gray-700 hover:text-gray-900 hover:bg-gray-50' : 'text-[#a0a0a0] hover:text-white hover:bg-[#333]' }} rounded-lg">My Subscriptions</a>
                            <a href="{{ route('cyber.orders') }}" class="block px-2 py-2 text-sm {{ request()->routeIs('home') ? 'text-gray-700 hover:text-gray-900 hover:bg-gray-50' : 'text-[#a0a0a0] hover:text-white hover:bg-[#333]' }} rounded-lg">Monana Food Orders</a>
                            <a href="{{ route('food.orders') }}" class="block px-2 py-2 text-sm {{ request()->routeIs('home') ? 'text-gray-700 hover:text-gray-900 hover:bg-gray-50' : 'text-[#a0a0a0] hover:text-white hover:bg-[#333]' }} rounded-lg">Food Orders</a>
                            @if(auth()->user()->is_admin)
                                <a href="{{ route('admin.dashboard') }}" class="block px-2 py-2 text-sm {{ request()->routeIs('home') ? 'text-indigo-600 hover:text-indigo-700 hover:bg-gray-50' : 'text-indigo-400 hover:text-indigo-300 hover:bg-[#333]' }} rounded-lg">Admin Panel</a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-2 py-2 text-sm {{ request()->routeIs('home') ? 'text-red-600 hover:text-red-700 hover:bg-gray-50' : 'text-red-400 hover:text-red-300 hover:bg-[#333]' }} rounded-lg">Logout</button>
                            </form>
                        </div>
                    @else
                        <div class="border-t {{ request()->routeIs('home') ? 'border-gray-200' : 'border-[#333]' }} pt-4 flex flex-col space-y-2">
                            <a href="{{ route('login') }}" class="block px-4 py-2 text-sm font-medium text-center {{ request()->routeIs('home') ? 'text-gray-900 bg-gray-100 hover:bg-gray-200' : 'text-white bg-[#333] hover:bg-[#404040]' }} rounded-lg transition-colors">Login</a>
                            <a href="{{ route('register') }}" class="block px-4 py-2 text-sm font-bold text-center {{ request()->routeIs('home') ? 'text-white bg-gray-900 hover:bg-gray-800' : 'text-gray-900 bg-white hover:bg-gray-100' }} rounded-lg transition-colors">Register</a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-14">
        @if(session('success'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
                <div class="bg-green-500/20 border border-green-500/50 text-green-400 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
                <div class="bg-red-500/20 border border-red-500/50 text-red-400 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-12 sm:mt-16 md:mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10 md:py-12">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 sm:gap-8">
                <div>
                    <div class="mb-4">
                        <img src="{{ asset('images/lg.jpeg') }}" alt="Monana Group Logo" class="h-12 w-auto object-contain">
                    </div>
                    <p class="text-sm text-gray-800">Your one-stop platform for cooked food delivery and kitchen essentials.</p>
                </div>

                <div>
                    <h4 class="text-sm font-bold text-gray-900 mb-4">Monana Food</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('cyber.index') }}" class="text-sm text-gray-800 hover:text-red-600 transition-colors">Order Food</a></li>
                        <li><a href="{{ route('cyber.menu') }}" class="text-sm text-gray-800 hover:text-red-600 transition-colors">View Menu</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-sm font-bold text-gray-900 mb-4">Monana Food</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('food.packages') }}" class="text-sm text-gray-800 hover:text-orange-500 transition-colors">Packages</a></li>
                        <li><a href="{{ route('food.custom') }}" class="text-sm text-gray-800 hover:text-orange-500 transition-colors">Custom Order</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-sm font-bold text-gray-900 mb-4">Contact</h4>
                    <ul class="space-y-2">
                        <li class="text-sm text-gray-800">WhatsApp: +255 7XX XXX XXX</li>
                        <li class="text-sm text-gray-800">Email: info@monana.com</li>
                    </ul>
                </div>
            </div>

            <div class="mt-8 pt-8 border-t border-gray-200 text-center">
                <p class="text-sm text-gray-800">&copy; {{ date('Y') }} Monana Platform. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>
