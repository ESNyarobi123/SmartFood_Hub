<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Monana Platform') - Food Delivery & Kitchen Essentials</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

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

        /* Page transition */
        .page-enter {
            animation: fadeInUp 0.4s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(12px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Focus visible for accessibility */
        *:focus-visible {
            outline: 2px solid #8b5cf6;
            outline-offset: 2px;
            border-radius: 4px;
        }

        /* Skeleton pulse */
        .skeleton {
            background: linear-gradient(90deg, #2d2d2d 25%, #3a3a3a 50%, #2d2d2d 75%);
            background-size: 200% 100%;
            animation: skeletonShimmer 1.5s ease-in-out infinite;
        }

        @keyframes skeletonShimmer {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        .home-page .skeleton {
            background: linear-gradient(90deg, #e5e7eb 25%, #f3f4f6 50%, #e5e7eb 75%);
            background-size: 200% 100%;
            animation: skeletonShimmer 1.5s ease-in-out infinite;
        }
    </style>
</head>
<body class="min-h-screen {{ request()->routeIs('home') ? 'home-page' : '' }}">
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 {{ request()->routeIs('home') ? '' : 'bg-[#1a1a1a]/90 backdrop-blur-lg border-b border-[#333]' }}"
         x-data="{ mobileMenuOpen: false, scrolled: false }"
         x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 20 })"
         :class="{ 'bg-white/95 backdrop-blur-lg border-b border-gray-200 shadow-sm': (scrolled || mobileMenuOpen) && {{ request()->routeIs('home') ? 'true' : 'false' }}, 'bg-transparent': !(scrolled || mobileMenuOpen) && {{ request()->routeIs('home') ? 'true' : 'false' }} }">
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
            <div x-show="mobileMenuOpen" x-transition class="lg:hidden pb-4 border-t {{ request()->routeIs('home') ? 'border-gray-200 bg-white/95 backdrop-blur-lg -mx-4 px-4 sm:-mx-6 sm:px-6 rounded-b-2xl' : 'border-[#333]' }}">
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

    <!-- Toast Notifications -->
    @if(session('success') || session('error'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-2 sm:translate-x-2"
         x-transition:enter-end="opacity-100 translate-y-0 sm:translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 sm:translate-x-0"
         x-transition:leave-end="opacity-0 translate-y-2 sm:translate-x-2"
         class="fixed bottom-4 right-4 sm:bottom-6 sm:right-6 z-[200] max-w-sm w-full pointer-events-auto">
        @if(session('success'))
        <div class="flex items-start gap-3 bg-[#1a2e1a] border border-green-500/40 text-green-300 px-4 py-3.5 rounded-xl shadow-2xl backdrop-blur-lg" role="alert">
            <svg class="w-5 h-5 text-green-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <div class="flex-1 text-sm font-medium">{{ session('success') }}</div>
            <button @click="show = false" class="text-green-400/60 hover:text-green-300 flex-shrink-0" aria-label="Close notification"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
        </div>
        @endif
        @if(session('error'))
        <div class="flex items-start gap-3 bg-[#2e1a1a] border border-red-500/40 text-red-300 px-4 py-3.5 rounded-xl shadow-2xl backdrop-blur-lg" role="alert">
            <svg class="w-5 h-5 text-red-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <div class="flex-1 text-sm font-medium">{{ session('error') }}</div>
            <button @click="show = false" class="text-red-400/60 hover:text-red-300 flex-shrink-0" aria-label="Close notification"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
        </div>
        @endif
    </div>
    @endif

    <!-- Main Content -->
    <main class="pt-14 page-enter">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="{{ request()->routeIs('home') ? 'bg-white border-t border-gray-200' : 'bg-[#0f0f0f] border-t border-[#222]' }} mt-12 sm:mt-16 md:mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10 md:py-12">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 sm:gap-8">
                <div>
                    <div class="mb-4">
                        <img src="{{ asset('images/lg.jpeg') }}" alt="Monana Group Logo" class="h-12 w-auto object-contain" loading="lazy">
                    </div>
                    <p class="text-sm {{ request()->routeIs('home') ? 'text-gray-600' : 'text-[#9ca3af]' }}">Your one-stop platform for cooked food delivery and kitchen essentials.</p>
                </div>

                <div>
                    <h4 class="text-sm font-bold {{ request()->routeIs('home') ? 'text-gray-900' : 'text-white' }} mb-4">Monana Food</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('cyber.index') }}" class="text-sm {{ request()->routeIs('home') ? 'text-gray-600 hover:text-red-600' : 'text-[#9ca3af] hover:text-[#00d4aa]' }} transition-colors">Order Food</a></li>
                        <li><a href="{{ route('cyber.menu') }}" class="text-sm {{ request()->routeIs('home') ? 'text-gray-600 hover:text-red-600' : 'text-[#9ca3af] hover:text-[#00d4aa]' }} transition-colors">View Menu</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-sm font-bold {{ request()->routeIs('home') ? 'text-gray-900' : 'text-white' }} mb-4">Monana Market</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('food.packages') }}" class="text-sm {{ request()->routeIs('home') ? 'text-gray-600 hover:text-orange-500' : 'text-[#9ca3af] hover:text-[#ff6b35]' }} transition-colors">Packages</a></li>
                        <li><a href="{{ route('food.custom') }}" class="text-sm {{ request()->routeIs('home') ? 'text-gray-600 hover:text-orange-500' : 'text-[#9ca3af] hover:text-[#ff6b35]' }} transition-colors">Custom Order</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-sm font-bold {{ request()->routeIs('home') ? 'text-gray-900' : 'text-white' }} mb-4">Contact</h4>
                    @php $waNumber = \App\Models\Setting::get('whatsapp_number', ''); $waLink = $waNumber ? 'https://wa.me/' . preg_replace('/[^0-9]/', '', $waNumber) : '#'; @endphp
                    <ul class="space-y-2">
                        <li class="text-sm {{ request()->routeIs('home') ? 'text-gray-600' : 'text-[#9ca3af]' }}">
                            @if($waNumber)
                                <a href="{{ $waLink }}" target="_blank" class="inline-flex items-center gap-1.5 hover:text-[#25D366] transition-colors">
                                    <svg class="w-4 h-4 text-[#25D366]" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/></svg>
                                    {{ $waNumber }}
                                </a>
                            @else
                                WhatsApp: —
                            @endif
                        </li>
                        <li class="text-sm {{ request()->routeIs('home') ? 'text-gray-600' : 'text-[#9ca3af]' }}">Email: info@monana.com</li>
                    </ul>
                </div>
            </div>

            <div class="mt-8 pt-8 {{ request()->routeIs('home') ? 'border-t border-gray-200' : 'border-t border-[#222]' }} text-center">
                <p class="text-sm {{ request()->routeIs('home') ? 'text-gray-600' : 'text-[#9ca3af]' }}">&copy; {{ date('Y') }} Monana Platform. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Floating WhatsApp Button -->
    @php $waNumFloat = \App\Models\Setting::get('whatsapp_number', ''); @endphp
    @if($waNumFloat)
    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $waNumFloat) }}" target="_blank" rel="noopener"
       x-data="{ show: false }" x-init="setTimeout(() => show = true, 1500)"
       x-show="show"
       x-transition:enter="transition ease-out duration-500"
       x-transition:enter-start="opacity-0 scale-75 translate-y-4"
       x-transition:enter-end="opacity-100 scale-100 translate-y-0"
       class="fixed bottom-6 right-6 z-[100] w-14 h-14 rounded-full shadow-2xl flex items-center justify-center transition-all hover:scale-110 active:scale-95"
       style="background: linear-gradient(135deg, #25D366, #128C7E); box-shadow: 0 4px 20px rgba(37,211,102,0.5);"
       aria-label="Chat on WhatsApp" title="WhatsApp: {{ $waNumFloat }}">
        <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347zM12 0C5.373 0 0 5.373 0 12c0 2.127.558 4.122 1.532 5.856L0 24l6.335-1.506A11.926 11.926 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.882a9.877 9.877 0 01-5.031-1.378l-.361-.214-3.732.888.924-3.638-.236-.374A9.832 9.832 0 012.118 12C2.118 6.527 6.527 2.118 12 2.118S21.882 6.527 21.882 12 17.473 21.882 12 21.882z"/>
        </svg>
    </a>
    @endif

    <!-- Back to Top Button -->
    <button x-data="{ visible: false }"
            x-init="window.addEventListener('scroll', () => { visible = window.scrollY > 400 })"
            x-show="visible"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-75"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-75"
            @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
            class="fixed bottom-6 left-6 z-[100] w-11 h-11 bg-[#242424] hover:bg-[#333] border border-[#444] text-white rounded-full shadow-xl flex items-center justify-center transition-all hover:scale-110 {{ request()->routeIs('home') ? 'bg-gray-900 hover:bg-gray-800 border-gray-700' : '' }}"
            aria-label="Scroll to top">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
    </button>

    <script src="https://unpkg.com/alpinejs@3.14.8/dist/cdn.min.js" defer></script>
</body>
</html>
