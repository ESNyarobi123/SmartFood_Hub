<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SmartFood Hub') }} - @yield('title', 'Home')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        html {
            scroll-behavior: smooth;
        }
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-fade-in {
            animation: fade-in 0.8s ease-out;
        }
        
        /* Desktop Navigation - Force display on medium+ screens */
        @media (min-width: 768px) {
            .desktop-nav {
                display: flex !important;
            }
        }
        
        /* Mobile Menu - Hide on medium+ screens */
        @media (min-width: 768px) {
            #mobileMenuToggle {
                display: none !important;
            }
            #mobileMenu {
                display: none !important;
            }
        }
    </style>
</head>
<body class="bg-blue-50 dark:bg-slate-900 text-slate-900 dark:text-slate-100">
    <nav class="bg-blue-700 dark:bg-slate-800 shadow-lg sticky top-0 z-50 backdrop-blur-sm bg-opacity-95">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="text-white text-xl font-bold flex items-center space-x-2">
                        <span class="text-2xl">🍽️</span>
                        <span class="hidden sm:inline">SmartFood Hub</span>
                        <span class="sm:hidden">SmartFood</span>
                    </a>
                </div>
                
                <!-- Desktop Navigation - Visible on Medium+ Screens (Tablets, Laptops, Desktops) -->
                <div class="desktop-nav hidden md:flex items-center flex-wrap gap-3">
                    <!-- Quick Navigation Buttons -->
                    <div class="flex items-center gap-2">
                        <a href="#foods" class="inline-flex items-center gap-1.5 bg-white/10 hover:bg-white/20 text-white px-3 py-1.5 rounded-full font-medium text-sm transition-all transform hover:scale-105 backdrop-blur-sm border border-white/20">
                            <span>🍽️</span>
                            <span>Vyakula</span>
                        </a>
                        <a href="#foods" class="inline-flex items-center gap-1.5 bg-white/10 hover:bg-white/20 text-white px-3 py-1.5 rounded-full font-medium text-sm transition-all transform hover:scale-105 backdrop-blur-sm border border-white/20">
                            <span>🔪</span>
                            <span>Bidhaa</span>
                        </a>
                        <a href="#packages" class="inline-flex items-center gap-1.5 bg-white/10 hover:bg-white/20 text-white px-3 py-1.5 rounded-full font-medium text-sm transition-all transform hover:scale-105 backdrop-blur-sm border border-white/20">
                            <span>📦</span>
                            <span>Packages</span>
                        </a>
                        <a href="#features" class="inline-flex items-center gap-1.5 bg-white/10 hover:bg-white/20 text-white px-3 py-1.5 rounded-full font-medium text-sm transition-all transform hover:scale-105 backdrop-blur-sm border border-white/20">
                            <span>⭐</span>
                            <span>Features</span>
                        </a>
                    </div>
                    
                    <!-- Auth Links -->
                    <div class="flex items-center space-x-4 ml-2">
                        @auth
                            @if(auth()->user()->is_admin)
                                <a href="{{ route('admin.dashboard') }}" class="text-blue-100 hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                                    Admin Dashboard
                                </a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-blue-100 hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                                    Logout
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="text-blue-100 hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                                Login
                            </a>
                            <a href="{{ route('register') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium">
                                Register
                            </a>
                        @endauth
                    </div>
                </div>
                
                <!-- Mobile Menu Toggle Button - Only on Small Screens -->
                <button id="mobileMenuToggle" class="md:hidden p-2 rounded-lg hover:bg-blue-600 text-white transition-colors focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-blue-700" aria-label="Toggle menu">
                    <svg id="menuIcon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <svg id="closeIcon" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- Mobile Menu Drawer - Only on Small Screens -->
        <div id="mobileMenu" class="md:hidden hidden bg-blue-800 dark:bg-slate-900 border-t border-blue-600 dark:border-slate-700">
            <div class="px-4 py-4 space-y-3">
                <!-- Quick Navigation Links -->
                <a href="#foods" class="block px-4 py-3 bg-white/10 hover:bg-white/20 text-white rounded-lg font-medium transition-all transform hover:scale-105 backdrop-blur-sm border border-white/20">
                    <span class="flex items-center gap-2">
                        <span>🍽️</span>
                        <span>Vyakula</span>
                    </span>
                </a>
                <a href="#foods" class="block px-4 py-3 bg-white/10 hover:bg-white/20 text-white rounded-lg font-medium transition-all transform hover:scale-105 backdrop-blur-sm border border-white/20">
                    <span class="flex items-center gap-2">
                        <span>🔪</span>
                        <span>Bidhaa</span>
                    </span>
                </a>
                <a href="#packages" class="block px-4 py-3 bg-white/10 hover:bg-white/20 text-white rounded-lg font-medium transition-all transform hover:scale-105 backdrop-blur-sm border border-white/20">
                    <span class="flex items-center gap-2">
                        <span>📦</span>
                        <span>Packages</span>
                    </span>
                </a>
                <a href="#features" class="block px-4 py-3 bg-white/10 hover:bg-white/20 text-white rounded-lg font-medium transition-all transform hover:scale-105 backdrop-blur-sm border border-white/20">
                    <span class="flex items-center gap-2">
                        <span>⭐</span>
                        <span>Features</span>
                    </span>
                </a>
                
                <!-- Divider -->
                <div class="border-t border-blue-600 dark:border-slate-700 my-3"></div>
                
                <!-- Auth Links -->
                <div class="space-y-2">
                    @auth
                        @if(auth()->user()->is_admin)
                            <a href="{{ route('admin.dashboard') }}" class="block px-4 py-3 bg-white/10 hover:bg-white/20 text-white rounded-lg font-medium transition-all">
                                Admin Dashboard
                            </a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-3 bg-white/10 hover:bg-white/20 text-white rounded-lg font-medium transition-all">
                                Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="block px-4 py-3 bg-white/10 hover:bg-white/20 text-white rounded-lg font-medium transition-all">
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="block px-4 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-medium text-center transition-all">
                            Register
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
    
    <script>
        // Mobile Menu Toggle
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuToggle = document.getElementById('mobileMenuToggle');
            const mobileMenu = document.getElementById('mobileMenu');
            const menuIcon = document.getElementById('menuIcon');
            const closeIcon = document.getElementById('closeIcon');
            
            if (mobileMenuToggle && mobileMenu) {
                mobileMenuToggle.addEventListener('click', function() {
                    const isHidden = mobileMenu.classList.contains('hidden');
                    
                    if (isHidden) {
                        mobileMenu.classList.remove('hidden');
                        menuIcon.classList.add('hidden');
                        closeIcon.classList.remove('hidden');
                    } else {
                        mobileMenu.classList.add('hidden');
                        menuIcon.classList.remove('hidden');
                        closeIcon.classList.add('hidden');
                    }
                });
                
                // Close menu when clicking on a link
                const menuLinks = mobileMenu.querySelectorAll('a, button');
                menuLinks.forEach(link => {
                    link.addEventListener('click', function() {
                        mobileMenu.classList.add('hidden');
                        menuIcon.classList.remove('hidden');
                        closeIcon.classList.add('hidden');
                    });
                });
                
                // Close menu when clicking outside
                document.addEventListener('click', function(event) {
                    if (!mobileMenu.contains(event.target) && !mobileMenuToggle.contains(event.target)) {
                        mobileMenu.classList.add('hidden');
                        menuIcon.classList.remove('hidden');
                        closeIcon.classList.add('hidden');
                    }
                });
            }
        });
    </script>

    <main>
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mx-auto max-w-7xl mt-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mx-auto max-w-7xl mt-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="bg-blue-700 dark:bg-slate-800 text-white mt-12 py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p>&copy; {{ date('Y') }} SmartFood Hub. All rights reserved.</p>
        </div>
    </footer>

    @php
        $whatsappNumber = \App\Models\Setting::get('whatsapp_number', '');
    @endphp

    @if($whatsappNumber)
        <style>
            /* WhatsApp Button - Fully Responsive */
            .whatsapp-float {
                position: fixed;
                z-index: 9999;
                display: flex;
                align-items: center;
                justify-content: center;
                background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
                color: white;
                border-radius: 50%;
                box-shadow: 0 4px 12px rgba(37, 211, 102, 0.4), 0 8px 24px rgba(37, 211, 102, 0.3);
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                cursor: pointer;
                text-decoration: none;
                -webkit-tap-highlight-color: transparent;
            }
            
            .whatsapp-float:hover {
                transform: scale(1.1);
                box-shadow: 0 6px 16px rgba(37, 211, 102, 0.5), 0 12px 32px rgba(37, 211, 102, 0.4);
            }
            
            .whatsapp-float:active {
                transform: scale(0.95);
            }
            
            /* Small phones (320px - 479px) */
            @media (max-width: 479px) {
                .whatsapp-float {
                    bottom: 1rem;
                    right: 1rem;
                    width: 56px;
                    height: 56px;
                    padding: 12px;
                }
                .whatsapp-float svg {
                    width: 32px;
                    height: 32px;
                }
            }
            
            /* Large phones (480px - 767px) */
            @media (min-width: 480px) and (max-width: 767px) {
                .whatsapp-float {
                    bottom: 1.25rem;
                    right: 1.25rem;
                    width: 64px;
                    height: 64px;
                    padding: 14px;
                }
                .whatsapp-float svg {
                    width: 36px;
                    height: 36px;
                }
            }
            
            /* Tablets (768px - 1023px) */
            @media (min-width: 768px) and (max-width: 1023px) {
                .whatsapp-float {
                    bottom: 1.5rem;
                    right: 1.5rem;
                    width: 72px;
                    height: 72px;
                    padding: 16px;
                }
                .whatsapp-float svg {
                    width: 40px;
                    height: 40px;
                }
            }
            
            /* Desktop (1024px - 1279px) */
            @media (min-width: 1024px) and (max-width: 1279px) {
                .whatsapp-float {
                    bottom: 2rem;
                    right: 2rem;
                    width: 80px;
                    height: 80px;
                    padding: 18px;
                }
                .whatsapp-float svg {
                    width: 44px;
                    height: 44px;
                }
            }
            
            /* Large Desktop (1280px+) */
            @media (min-width: 1280px) {
                .whatsapp-float {
                    bottom: 2.5rem;
                    right: 2.5rem;
                    width: 88px;
                    height: 88px;
                    padding: 20px;
                }
                .whatsapp-float svg {
                    width: 48px;
                    height: 48px;
                }
            }
            
            /* Tooltip - Responsive */
            .whatsapp-tooltip {
                position: absolute;
                bottom: 100%;
                right: 0;
                margin-bottom: 12px;
                background: rgba(15, 23, 42, 0.95);
                color: white;
                padding: 8px 12px;
                border-radius: 8px;
                font-size: 14px;
                white-space: nowrap;
                opacity: 0;
                pointer-events: none;
                transform: translateY(4px);
                transition: all 0.3s ease;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            }
            
            .whatsapp-float:hover .whatsapp-tooltip {
                opacity: 1;
                transform: translateY(0);
            }
            
            /* Hide tooltip on mobile */
            @media (max-width: 767px) {
                .whatsapp-tooltip {
                    display: none;
                }
            }
            
            /* Pulse animation for attention */
            @keyframes whatsapp-pulse {
                0%, 100% {
                    box-shadow: 0 4px 12px rgba(37, 211, 102, 0.4), 0 8px 24px rgba(37, 211, 102, 0.3);
                }
                50% {
                    box-shadow: 0 4px 12px rgba(37, 211, 102, 0.6), 0 8px 24px rgba(37, 211, 102, 0.5), 0 0 0 8px rgba(37, 211, 102, 0.2);
                }
            }
            
            .whatsapp-float {
                animation: whatsapp-pulse 2s ease-in-out infinite;
            }
        </style>
        
        <a 
            href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $whatsappNumber) }}" 
            target="_blank"
            rel="noopener noreferrer"
            class="whatsapp-float group"
            aria-label="Chat with us on WhatsApp"
        >
            <svg fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
            </svg>
            <span class="whatsapp-tooltip">
                Chat with us on WhatsApp
            </span>
        </a>
    @endif
</body>
</html>
