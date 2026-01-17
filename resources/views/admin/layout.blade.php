<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin - Monana Platform - @yield('title', 'Dashboard')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            /* Background Gradients */
            --bg-gradient-start: #0a0a0f;
            --bg-gradient-mid: #0d0d15;
            --bg-gradient-end: #12121a;
            
            /* Glass Effects */
            --glass-bg: rgba(255, 255, 255, 0.03);
            --glass-border: rgba(255, 255, 255, 0.08);
            --glass-hover: rgba(255, 255, 255, 0.06);
            
            /* Accent Colors - Vibrant */
            --accent-cyber: #00ffc8;
            --accent-cyber-glow: rgba(0, 255, 200, 0.4);
            --accent-food: #ff7b54;
            --accent-food-glow: rgba(255, 123, 84, 0.4);
            --accent-primary: #8b5cf6;
            --accent-primary-glow: rgba(139, 92, 246, 0.4);
            --accent-secondary: #06b6d4;
            
            /* Gradient Accents */
            --gradient-purple: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-blue: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --gradient-orange: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            --gradient-green: linear-gradient(135deg, #00f5a0 0%, #00d9f5 100%);
            --gradient-pink: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --gradient-dark: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            
            /* Text Colors */
            --text-primary: #ffffff;
            --text-secondary: #a8b2c1;
            --text-muted: #5c6b7f;
            
            /* Status Colors */
            --status-success: #10b981;
            --status-warning: #f59e0b;
            --status-error: #ef4444;
            --status-info: #3b82f6;
        }

        * {
            scrollbar-width: thin;
            scrollbar-color: rgba(139, 92, 246, 0.3) transparent;
        }

        *::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        *::-webkit-scrollbar-track {
            background: transparent;
        }

        *::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 10px;
        }

        *::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #764ba2, #f093fb);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, var(--bg-gradient-start) 0%, var(--bg-gradient-mid) 50%, var(--bg-gradient-end) 100%);
            background-attachment: fixed;
            color: var(--text-primary);
            min-height: 100vh;
        }

        /* Animated Background Orbs */
        body::before {
            content: '';
            position: fixed;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: 
                radial-gradient(circle at 20% 80%, rgba(139, 92, 246, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(6, 182, 212, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(245, 87, 108, 0.05) 0%, transparent 40%);
            animation: float 20s ease-in-out infinite;
            pointer-events: none;
            z-index: 0;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            25% { transform: translate(2%, 2%) rotate(1deg); }
            50% { transform: translate(0, 4%) rotate(0deg); }
            75% { transform: translate(-2%, 2%) rotate(-1deg); }
        }

        /* Sidebar Styling */
        .sidebar {
            background: rgba(10, 10, 18, 0.95);
            backdrop-filter: blur(20px);
            border-right: 1px solid var(--glass-border);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar.collapsed {
            transform: translateX(calc(-100% + 80px));
        }

        .sidebar.collapsed .sidebar-content {
            opacity: 0;
            pointer-events: none;
        }

        .sidebar.collapsed .sidebar-icon-only {
            opacity: 1;
            pointer-events: all;
        }

        .sidebar:not(.collapsed) .sidebar-icon-only {
            opacity: 0;
            pointer-events: none;
        }

        .sidebar-icon-only {
            position: absolute;
            left: 0;
            top: 0;
            width: 80px;
            height: 100%;
            display: flex;
            flex-direction: column;
            padding: 1rem 0;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }

        .sidebar-content {
            transition: opacity 0.3s ease;
        }

        /* Auto-hide on desktop - show on hover */
        @media (min-width: 1024px) {
            .sidebar:not(.pinned) {
                transform: translateX(calc(-100% + 80px));
            }

            .sidebar:not(.pinned) .sidebar-content {
                opacity: 0;
                pointer-events: none;
            }

            .sidebar:not(.pinned) .sidebar-icon-only {
                opacity: 1;
                pointer-events: all;
            }

            .sidebar:not(.pinned):hover {
                transform: translateX(0);
            }

            .sidebar:not(.pinned):hover .sidebar-content {
                opacity: 1;
                pointer-events: all;
            }

            .sidebar:not(.pinned):hover .sidebar-icon-only {
                opacity: 0;
                pointer-events: none;
            }

            .sidebar.pinned {
                transform: translateX(0) !important;
            }

            .sidebar.pinned .sidebar-content {
                opacity: 1 !important;
                pointer-events: all !important;
            }

            .sidebar.pinned .sidebar-icon-only {
                opacity: 0 !important;
                pointer-events: none !important;
            }
        }

        .sidebar-item {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .sidebar-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 0;
            background: var(--gradient-purple);
            opacity: 0;
            transition: all 0.3s ease;
        }

        .sidebar-item:hover {
            background: var(--glass-hover);
            transform: translateX(4px);
        }

        .sidebar-item:hover::before {
            width: 3px;
            opacity: 1;
        }

        .sidebar-item.active {
            background: linear-gradient(90deg, rgba(139, 92, 246, 0.15) 0%, transparent 100%);
        }

        .sidebar-item.active::before {
            width: 3px;
            opacity: 1;
        }

        /* Service Switcher */
        .service-tab {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .service-tab::after {
            content: '';
            position: absolute;
            inset: 0;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .service-tab.active-cyber {
            background: linear-gradient(135deg, rgba(0, 255, 200, 0.15) 0%, rgba(0, 217, 245, 0.05) 100%);
            border-color: var(--accent-cyber);
            box-shadow: 0 0 30px var(--accent-cyber-glow), inset 0 0 30px rgba(0, 255, 200, 0.05);
        }

        .service-tab.active-cyber::after {
            background: radial-gradient(circle at center, rgba(0, 255, 200, 0.1) 0%, transparent 70%);
            opacity: 1;
        }

        .service-tab.active-food {
            background: linear-gradient(135deg, rgba(255, 123, 84, 0.15) 0%, rgba(254, 225, 64, 0.05) 100%);
            border-color: var(--accent-food);
            box-shadow: 0 0 30px var(--accent-food-glow), inset 0 0 30px rgba(255, 123, 84, 0.05);
        }

        .service-tab.active-food::after {
            background: radial-gradient(circle at center, rgba(255, 123, 84, 0.1) 0%, transparent 70%);
            opacity: 1;
        }

        /* Cards */
        .card {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border: 1px solid var(--glass-border);
            border-radius: 16px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
        }

        .card:hover {
            border-color: rgba(139, 92, 246, 0.3);
            transform: translateY(-2px);
            box-shadow: 0 20px 40px -20px rgba(139, 92, 246, 0.3);
        }

        /* Stat Cards */
        .stat-card {
            position: relative;
            overflow: hidden;
        }

        .stat-card.cyber {
            background: linear-gradient(135deg, rgba(0, 255, 200, 0.08) 0%, rgba(6, 182, 212, 0.03) 100%);
            border-color: rgba(0, 255, 200, 0.2);
        }

        .stat-card.cyber:hover {
            box-shadow: 0 20px 40px -20px var(--accent-cyber-glow);
        }

        .stat-card.food {
            background: linear-gradient(135deg, rgba(255, 123, 84, 0.08) 0%, rgba(254, 225, 64, 0.03) 100%);
            border-color: rgba(255, 123, 84, 0.2);
        }

        .stat-card.food:hover {
            box-shadow: 0 20px 40px -20px var(--accent-food-glow);
        }

        .stat-card.purple {
            background: linear-gradient(135deg, rgba(139, 92, 246, 0.08) 0%, rgba(245, 87, 108, 0.03) 100%);
            border-color: rgba(139, 92, 246, 0.2);
        }

        .stat-card.purple:hover {
            box-shadow: 0 20px 40px -20px var(--accent-primary-glow);
        }

        /* Buttons */
        .btn-primary {
            background: var(--gradient-purple);
            border: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px -10px var(--accent-primary-glow);
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        .btn-cyber {
            background: linear-gradient(135deg, #00ffc8 0%, #00d9f5 100%);
            color: #0a0a0f;
        }

        .btn-cyber:hover {
            box-shadow: 0 10px 30px -10px var(--accent-cyber-glow);
        }

        .btn-food {
            background: linear-gradient(135deg, #ff7b54 0%, #fee140 100%);
            color: #0a0a0f;
        }

        .btn-food:hover {
            box-shadow: 0 10px 30px -10px var(--accent-food-glow);
        }

        /* Glowing Elements */
        .glow-cyber {
            box-shadow: 0 0 40px var(--accent-cyber-glow);
        }

        .glow-food {
            box-shadow: 0 0 40px var(--accent-food-glow);
        }

        .glow-purple {
            box-shadow: 0 0 40px var(--accent-primary-glow);
        }

        /* Badge Styling */
        .badge {
            position: relative;
            overflow: hidden;
        }

        .badge.cyber {
            background: linear-gradient(135deg, #00ffc8, #00d9f5);
            color: #0a0a0f;
            box-shadow: 0 0 15px var(--accent-cyber-glow);
        }

        .badge.food {
            background: linear-gradient(135deg, #ff7b54, #fee140);
            color: #0a0a0f;
            box-shadow: 0 0 15px var(--accent-food-glow);
        }

        /* Top Bar Glass */
        .top-bar {
            background: rgba(10, 10, 18, 0.8);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--glass-border);
        }

        /* Input Styling */
        input, select, textarea {
            background: rgba(255, 255, 255, 0.03) !important;
            border: 1px solid var(--glass-border) !important;
            transition: all 0.3s ease !important;
        }

        input:focus, select:focus, textarea:focus {
            border-color: var(--accent-primary) !important;
            box-shadow: 0 0 20px rgba(139, 92, 246, 0.2) !important;
            outline: none !important;
        }

        /* Select dropdowns - ensure visibility */
        select {
            background-color: rgba(255, 255, 255, 0.05) !important;
            color: #ffffff !important;
        }

        select option {
            background-color: #1a1a1a !important;
            color: #ffffff !important;
        }

        select option:checked {
            background-color: #ff7b54 !important;
            color: #ffffff !important;
        }

        select option:hover {
            background-color: #2d2d2d !important;
        }

        /* Table Styling */
        table {
            border-collapse: separate;
            border-spacing: 0;
        }

        th {
            background: rgba(139, 92, 246, 0.1);
            border-bottom: 1px solid var(--glass-border);
        }

        tr {
            transition: all 0.2s ease;
        }

        tbody tr:hover {
            background: var(--glass-hover);
        }

        td {
            border-bottom: 1px solid var(--glass-border);
        }

        /* Animation Classes */
        @keyframes pulse-glow {
            0%, 100% { 
                box-shadow: 0 0 20px var(--accent-primary-glow);
            }
            50% { 
                box-shadow: 0 0 40px var(--accent-primary-glow);
            }
        }

        .animate-pulse-glow {
            animation: pulse-glow 2s ease-in-out infinite;
        }

        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }

        .shimmer {
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.05), transparent);
            background-size: 200% 100%;
            animation: shimmer 2s infinite;
        }

        /* Logo Animation */
        .logo-icon {
            background: var(--gradient-purple);
            position: relative;
            overflow: hidden;
        }

        .logo-icon::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: conic-gradient(from 0deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            animation: rotate 3s linear infinite;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* Status Dots */
        .status-dot {
            position: relative;
        }

        .status-dot::after {
            content: '';
            position: absolute;
            inset: -2px;
            border-radius: 50%;
            background: inherit;
            opacity: 0.4;
            animation: ping 1.5s cubic-bezier(0, 0, 0.2, 1) infinite;
        }

        @keyframes ping {
            75%, 100% {
                transform: scale(2);
                opacity: 0;
            }
        }

        /* Success/Error Alert Styling */
        .alert-success {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.15) 0%, rgba(6, 182, 212, 0.05) 100%);
            border: 1px solid rgba(16, 185, 129, 0.3);
            box-shadow: 0 0 30px rgba(16, 185, 129, 0.1);
        }

        .alert-error {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.15) 0%, rgba(245, 87, 108, 0.05) 100%);
            border: 1px solid rgba(239, 68, 68, 0.3);
            box-shadow: 0 0 30px rgba(239, 68, 68, 0.1);
        }

        /* Toggle Button */
        .sidebar-toggle {
            position: fixed;
            top: 20px;
            z-index: 60;
            width: 40px;
            height: 40px;
            background: rgba(139, 92, 246, 0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(139, 92, 246, 0.3);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .sidebar-toggle:hover {
            background: rgba(139, 92, 246, 0.3);
            border-color: rgba(139, 92, 246, 0.5);
            transform: scale(1.1);
            box-shadow: 0 0 20px rgba(139, 92, 246, 0.3);
        }

        @media (min-width: 1024px) {
            .sidebar-toggle {
                left: 100px;
            }

            .sidebar.pinned ~ main .sidebar-toggle,
            .sidebar:not(.pinned):hover ~ main .sidebar-toggle {
                left: 280px;
            }
        }

        /* Main content adjustment */
        @media (min-width: 1024px) {
            main {
                transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                margin-left: 80px;
            }

            .sidebar.pinned ~ main,
            .sidebar:not(.pinned):hover ~ main {
                margin-left: 256px;
            }
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
                width: 280px;
                box-shadow: 4px 0 20px rgba(0, 0, 0, 0.5);
            }
            .sidebar.open {
                transform: translateX(0);
            }
            .sidebar ~ main {
                margin-left: 0 !important;
                padding: 0.75rem;
            }
            .sidebar-toggle {
                left: 20px !important;
            }
            .top-bar {
                padding: 0.75rem 1rem;
            }
            .card {
                padding: 1.25rem;
            }
        }
        
        @media (max-width: 640px) {
            .sidebar {
                width: 100%;
            }
            .main-content {
                padding: 0.5rem;
            }
            .top-bar {
                padding: 0.5rem 0.75rem;
            }
            .top-bar h1 {
                font-size: 1.25rem;
            }
            .card {
                padding: 1rem;
            }
            .stat-card {
                padding: 1rem;
            }
        }
    </style>
</head>
<body class="min-h-screen">
    <div class="flex relative z-10">
        <!-- Sidebar Toggle Button -->
        <button id="sidebarToggle" class="sidebar-toggle lg:flex hidden items-center justify-center" title="Toggle Sidebar">
            <svg id="toggleIcon" class="w-5 h-5 text-[#8b5cf6]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>

        <!-- Sidebar -->
        <aside id="sidebar" class="sidebar fixed left-0 top-0 z-50 h-screen w-64 flex flex-col lg:translate-x-0 -translate-x-full">
            <!-- Icon Only Sidebar (shown when collapsed) -->
            <div class="sidebar-icon-only">
                <div class="flex flex-col items-center space-y-4">
                    <a href="{{ route('admin.dashboard') }}" class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: var(--gradient-purple);">
                        <span class="text-lg font-bold text-white">M</span>
                    </a>
                    <a href="{{ route('admin.cyber.dashboard') }}" class="w-12 h-12 rounded-xl flex items-center justify-center hover:bg-white/10 transition-colors" title="Monana Food">
                        <svg class="w-6 h-6 text-[#00ffc8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </a>
                    <a href="{{ route('admin.food.dashboard') }}" class="w-12 h-12 rounded-xl flex items-center justify-center hover:bg-white/10 transition-colors" title="Monana Market">
                        <svg class="w-6 h-6 text-[#ff7b54]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </a>
                    <div class="flex-1"></div>
                    <a href="{{ route('admin.settings.index') }}" class="w-12 h-12 rounded-xl flex items-center justify-center hover:bg-white/10 transition-colors" title="Settings">
                        <svg class="w-6 h-6 text-[#a8b2c1]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Full Sidebar Content -->
            <div class="sidebar-content flex flex-col flex-1">
            <!-- Logo -->
            <div class="p-6 border-b border-white/5">
                <div class="flex items-center space-x-3">
                    <div class="logo-icon w-11 h-11 rounded-xl flex items-center justify-center relative">
                        <span class="text-xl font-bold text-white relative z-10">M</span>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold bg-gradient-to-r from-white to-purple-200 bg-clip-text text-transparent">Monana</h1>
                        <p class="text-xs text-[#5c6b7f]">Admin Panel</p>
                    </div>
                </div>
            </div>

            <!-- Service Switcher -->
            <div class="p-4 border-b border-white/5">
                <p class="text-[10px] text-[#5c6b7f] uppercase tracking-widest mb-3 font-semibold">Active Service</p>
                <div class="grid grid-cols-2 gap-2">
                    <a href="{{ route('admin.cyber.dashboard') }}" 
                       class="service-tab flex flex-col items-center p-3 rounded-xl border border-white/10 {{ request()->is('admin/cyber*') ? 'active-cyber' : 'hover:bg-white/5' }}">
                        <svg class="w-5 h-5 mb-1 {{ request()->is('admin/cyber*') ? 'text-[#00ffc8]' : 'text-[#5c6b7f]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-xs font-semibold {{ request()->is('admin/cyber*') ? 'text-[#00ffc8]' : 'text-[#a8b2c1]' }}">Food</span>
                    </a>
                    <a href="{{ route('admin.food.dashboard') }}" 
                       class="service-tab flex flex-col items-center p-3 rounded-xl border border-white/10 {{ request()->is('admin/food*') ? 'active-food' : 'hover:bg-white/5' }}">
                        <svg class="w-5 h-5 mb-1 {{ request()->is('admin/food*') ? 'text-[#ff7b54]' : 'text-[#5c6b7f]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span class="text-xs font-semibold {{ request()->is('admin/food*') ? 'text-[#ff7b54]' : 'text-[#a8b2c1]' }}">Market</span>
                    </a>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 overflow-y-auto py-4">
                <!-- Overview -->
                <div class="px-4 mb-4">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded-xl {{ request()->routeIs('admin.dashboard') && !request()->is('admin/cyber*') && !request()->is('admin/food*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 text-[#8b5cf6]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                        </svg>
                        <span class="text-sm font-medium text-[#e0e8f0]">Overview</span>
                    </a>
                </div>

                @if(request()->is('admin/cyber*'))
                <!-- Monana Food Menu -->
                <div class="px-4 mb-2">
                    <p class="text-[10px] text-[#5c6b7f] uppercase tracking-widest mb-2 px-4 font-semibold">Monana Food</p>
                </div>
                <div class="px-4 space-y-1">
                    <a href="{{ route('admin.cyber.dashboard') }}" 
                       class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded-xl {{ request()->routeIs('admin.cyber.dashboard') ? 'active' : '' }}">
                        <svg class="w-5 h-5 text-[#00ffc8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <span class="text-sm font-medium text-[#e0e8f0]">Dashboard</span>
                    </a>
                    <a href="{{ route('admin.cyber.menu.index') }}" 
                       class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded-xl {{ request()->routeIs('admin.cyber.menu.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 text-[#00ffc8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        <span class="text-sm font-medium text-[#e0e8f0]">Menu Items</span>
                    </a>
                    <a href="{{ route('admin.cyber.meal-slots.index') }}" 
                       class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded-xl {{ request()->routeIs('admin.cyber.meal-slots.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 text-[#00ffc8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-sm font-medium text-[#e0e8f0]">Meal Slots</span>
                    </a>
                    <a href="{{ route('admin.cyber.orders.index') }}" 
                       class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded-xl {{ request()->routeIs('admin.cyber.orders.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 text-[#00ffc8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <span class="text-sm font-medium text-[#e0e8f0]">Orders</span>
                        @php $cyberPendingOrders = \App\Models\Cyber\Order::where('status', 'pending')->count(); @endphp
                        @if($cyberPendingOrders > 0)
                            <span class="badge cyber ml-auto px-2.5 py-1 text-[10px] font-bold rounded-full">{{ $cyberPendingOrders }}</span>
                        @endif
                    </a>
                    <a href="{{ route('admin.cyber.revenue') }}" 
                       class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded-xl {{ request()->routeIs('admin.cyber.revenue') ? 'active' : '' }}">
                        <svg class="w-5 h-5 text-[#00ffc8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-sm font-medium text-[#e0e8f0]">Revenue</span>
                    </a>
                </div>
                @endif

                @if(request()->is('admin/food*'))
                <!-- Monana Market Menu -->
                <div class="px-4 mb-2">
                    <p class="text-[10px] text-[#5c6b7f] uppercase tracking-widest mb-2 px-4 font-semibold">Monana Market</p>
                </div>
                <div class="px-4 space-y-1">
                    <a href="{{ route('admin.food.dashboard') }}" 
                       class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded-xl {{ request()->routeIs('admin.food.dashboard') ? 'active' : '' }}">
                        <svg class="w-5 h-5 text-[#ff7b54]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <span class="text-sm font-medium text-[#e0e8f0]">Dashboard</span>
                    </a>
                    <a href="{{ route('admin.food.products.index') }}" 
                       class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded-xl {{ request()->routeIs('admin.food.products.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 text-[#ff7b54]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        <span class="text-sm font-medium text-[#e0e8f0]">Products</span>
                    </a>
                    <a href="{{ route('admin.food.packages.index') }}" 
                       class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded-xl {{ request()->routeIs('admin.food.packages.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 text-[#ff7b54]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                        </svg>
                        <span class="text-sm font-medium text-[#e0e8f0]">Packages</span>
                    </a>
                    <a href="{{ route('admin.food.subscriptions.index') }}" 
                       class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded-xl {{ request()->routeIs('admin.food.subscriptions.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 text-[#ff7b54]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        <span class="text-sm font-medium text-[#e0e8f0]">Subscriptions</span>
                        @php $activeSubscriptions = \App\Models\Food\Subscription::where('status', 'active')->count(); @endphp
                        @if($activeSubscriptions > 0)
                            <span class="badge food ml-auto px-2.5 py-1 text-[10px] font-bold rounded-full">{{ $activeSubscriptions }}</span>
                        @endif
                    </a>
                    <a href="{{ route('admin.food.orders.index') }}" 
                       class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded-xl {{ request()->routeIs('admin.food.orders.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 text-[#ff7b54]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <span class="text-sm font-medium text-[#e0e8f0]">Orders</span>
                        @php $foodPendingOrders = \App\Models\Food\Order::where('status', 'pending')->count(); @endphp
                        @if($foodPendingOrders > 0)
                            <span class="badge food ml-auto px-2.5 py-1 text-[10px] font-bold rounded-full">{{ $foodPendingOrders }}</span>
                        @endif
                    </a>
                    <a href="{{ route('admin.food.revenue') }}" 
                       class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded-xl {{ request()->routeIs('admin.food.revenue') ? 'active' : '' }}">
                        <svg class="w-5 h-5 text-[#ff7b54]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-sm font-medium text-[#e0e8f0]">Revenue</span>
                    </a>
                </div>
                @endif

                <!-- Settings (Always visible) -->
                <div class="px-4 mt-6 pt-4 border-t border-white/5">
                    <a href="{{ route('admin.settings.index') }}" 
                       class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded-xl {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 text-[#a8b2c1]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span class="text-sm font-medium text-[#e0e8f0]">Settings</span>
                    </a>
                </div>
            </nav>

            <!-- User Profile -->
            <div class="p-4 border-t border-white/5">
                <div class="flex items-center space-x-3 mb-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: var(--gradient-purple);">
                        <span class="text-sm font-bold text-white">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-[#5c6b7f] truncate">{{ auth()->user()->email }}</p>
                    </div>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('home') }}" target="_blank" class="flex-1 flex items-center justify-center px-3 py-2.5 text-xs font-medium text-[#a8b2c1] hover:text-white bg-white/5 hover:bg-white/10 rounded-xl transition-all duration-300">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                        View Site
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center px-3 py-2.5 text-xs font-medium text-red-400 hover:text-red-300 bg-red-500/10 hover:bg-red-500/20 rounded-xl transition-all duration-300">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main id="mainContent" class="flex-1 min-h-screen relative z-10">
            <!-- Top Bar -->
            <header class="top-bar sticky top-0 z-40">
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center space-x-4">
                        <!-- Mobile Toggle -->
                        <button id="mobileSidebarToggle" class="lg:hidden fixed top-4 left-4 z-50 p-3 bg-white/10 backdrop-blur-lg border border-white/20 rounded-xl hover:bg-white/20 transition-colors shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                            <svg class="w-6 h-6 text-[#a8b2c1]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                        <div>
                            <h2 class="text-xl font-bold bg-gradient-to-r from-white to-purple-200 bg-clip-text text-transparent">@yield('title', 'Dashboard')</h2>
                            <p class="text-sm text-[#5c6b7f]">@yield('subtitle', 'Welcome back!')</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <!-- Date/Time Display -->
                        <div class="hidden md:flex items-center space-x-3 px-4 py-2 bg-white/5 rounded-xl border border-white/5">
                            <div class="w-2 h-2 rounded-full bg-green-400 status-dot"></div>
                            <span id="currentDate" class="text-xs font-medium text-[#a8b2c1]">{{ \Carbon\Carbon::now('Africa/Dar_es_Salaam')->format('M d, Y') }}</span>
                            <span class="text-xs text-[#5c6b7f]">|</span>
                            <span id="currentTime" class="text-xs text-[#5c6b7f]">{{ \Carbon\Carbon::now('Africa/Dar_es_Salaam')->format('h:i A') }}</span>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="p-6">
                <!-- Success/Error Messages -->
                @if(session('success'))
                    <div class="alert-success mb-6 px-5 py-4 rounded-xl flex items-center">
                        <div class="w-10 h-10 rounded-xl bg-green-500/20 flex items-center justify-center mr-4">
                            <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-green-400">Success</p>
                            <p class="text-sm text-green-300/80">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert-error mb-6 px-5 py-4 rounded-xl flex items-center">
                        <div class="w-10 h-10 rounded-xl bg-red-500/20 flex items-center justify-center mr-4">
                            <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-red-400">Error</p>
                            <p class="text-sm text-red-300/80">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <script>
        // Sidebar Elements
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const toggleIcon = document.getElementById('toggleIcon');
        const mobileSidebarToggle = document.getElementById('mobileSidebarToggle');

        // Check localStorage for pinned state
        const isPinned = localStorage.getItem('sidebarPinned') === 'true';

        // Initialize sidebar state
        if (window.innerWidth >= 1024) {
            if (isPinned) {
                sidebar.classList.add('pinned');
                // Set icon to X when pinned
                if (toggleIcon) {
                    toggleIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>';
                }
            }
        } else {
            sidebar.classList.add('-translate-x-full');
        }

        // Desktop Toggle (Pin/Unpin)
        sidebarToggle?.addEventListener('click', (e) => {
            e.stopPropagation();
            if (window.innerWidth >= 1024) {
                sidebar.classList.toggle('pinned');
                const pinned = sidebar.classList.contains('pinned');
                localStorage.setItem('sidebarPinned', pinned);
                
                // Update icon
                if (pinned) {
                    toggleIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>';
                } else {
                    toggleIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>';
                }
            }
        });

        // Mobile Toggle
        mobileSidebarToggle?.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
            sidebar.classList.toggle('open');
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', (e) => {
            if (window.innerWidth < 1024) {
                if (!sidebar.contains(e.target) && 
                    !mobileSidebarToggle?.contains(e.target) && 
                    !sidebarToggle?.contains(e.target)) {
                    sidebar.classList.add('-translate-x-full');
                    sidebar.classList.remove('open');
                }
            }
        });

        // Auto-hide on mouse leave (desktop only, if not pinned)
        if (window.innerWidth >= 1024) {
            sidebar.addEventListener('mouseleave', () => {
                if (!sidebar.classList.contains('pinned')) {
                    // Sidebar will auto-hide via CSS :hover
                }
            });
        }

        // Handle window resize
        window.addEventListener('resize', () => {
            if (window.innerWidth < 1024) {
                sidebar.classList.remove('pinned');
                sidebar.classList.add('-translate-x-full');
            } else {
                if (isPinned) {
                    sidebar.classList.add('pinned');
                }
                sidebar.classList.remove('-translate-x-full');
            }
        });

        // Live Clock - Dar es Salaam Time
        function updateClock() {
            const now = new Date();
            // Convert to Dar es Salaam time (UTC+3)
            const darEsSalaamTime = new Date(now.toLocaleString('en-US', { timeZone: 'Africa/Dar_es_Salaam' }));
            
            const dateElement = document.getElementById('currentDate');
            const timeElement = document.getElementById('currentTime');
            
            if (dateElement && timeElement) {
                // Format date: Jan 15, 2026
                const dateOptions = { month: 'short', day: 'numeric', year: 'numeric' };
                dateElement.textContent = darEsSalaamTime.toLocaleDateString('en-US', dateOptions);
                
                // Format time: 07:50 PM
                const timeOptions = { hour: 'numeric', minute: '2-digit', hour12: true };
                timeElement.textContent = darEsSalaamTime.toLocaleTimeString('en-US', timeOptions);
            }
        }
        
        // Update clock immediately and then every second
        updateClock();
        setInterval(updateClock, 1000);
    </script>
</body>
</html>
