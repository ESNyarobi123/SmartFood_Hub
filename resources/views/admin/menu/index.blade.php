@extends('admin.layout')

@section('title', 'Menu Management')

@section('content')
<!-- Header Section -->
<div class="mb-8 animate-slide-in">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-4xl font-bold bg-gradient-to-r from-orange-500 via-pink-500 via-purple-500 via-blue-500 to-cyan-500 bg-clip-text text-transparent mb-2 animate-pulse">
                Menu Management
            </h1>
            <p class="text-slate-600 dark:text-slate-400 text-lg">Manage foods, products, categories, and subscription packages with ease</p>
        </div>
        <div class="mt-4 md:mt-0 flex items-center space-x-3">
            <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-gradient-to-r from-orange-500 via-pink-500 to-purple-600 hover:from-orange-600 hover:via-pink-600 hover:to-purple-700 text-white rounded-xl font-semibold transition-all transform hover:scale-105 shadow-lg hover:shadow-2xl flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span>Dashboard</span>
            </a>
        </div>
    </div>
</div>

<!-- Quick Action Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- New Category Card -->
    <a href="{{ route('admin.menu.categories.create') }}" class="group relative overflow-hidden bg-gradient-to-br from-orange-500 via-pink-500 to-rose-600 rounded-2xl shadow-xl p-6 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 animate-slide-in">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500"></div>
        <div class="relative z-10">
            <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center mb-4 group-hover:rotate-12 transition-transform">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
            </div>
            <h3 class="text-white font-bold text-lg mb-2">New Category</h3>
            <p class="text-blue-100 text-sm">Create food or kitchen category</p>
            <div class="mt-4 flex items-center text-white font-semibold text-sm group-hover:translate-x-2 transition-transform">
                <span>Add Now</span>
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </div>
        </div>
        <span class="absolute top-4 right-4 px-2 py-1 bg-red-500 text-white text-xs font-bold rounded-full">0</span>
    </a>

    <!-- New Food Item Card -->
    <a href="{{ route('admin.menu.food-items.create') }}" class="group relative overflow-hidden bg-gradient-to-br from-yellow-400 via-orange-500 to-red-500 rounded-2xl shadow-xl p-6 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 animate-slide-in" style="animation-delay: 0.1s">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500"></div>
        <div class="relative z-10">
            <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center mb-4 group-hover:rotate-12 transition-transform">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
            </div>
            <h3 class="text-white font-bold text-lg mb-2">New Food Item</h3>
            <p class="text-green-100 text-sm">Add delicious food to menu</p>
            <div class="mt-4 flex items-center text-white font-semibold text-sm group-hover:translate-x-2 transition-transform">
                <span>Add Now</span>
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </div>
        </div>
    </a>

    <!-- New Product Card -->
    <a href="{{ route('admin.menu.kitchen-products.create') }}" class="group relative overflow-hidden bg-gradient-to-br from-cyan-400 via-blue-500 to-indigo-600 rounded-2xl shadow-xl p-6 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 animate-slide-in" style="animation-delay: 0.2s">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500"></div>
        <div class="relative z-10">
            <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center mb-4 group-hover:rotate-12 transition-transform">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
            </div>
            <h3 class="text-white font-bold text-lg mb-2">New Product</h3>
            <p class="text-teal-100 text-sm">Add kitchen product</p>
            <div class="mt-4 flex items-center text-white font-semibold text-sm group-hover:translate-x-2 transition-transform">
                <span>Add Now</span>
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </div>
        </div>
    </a>

    <!-- New Package Card -->
    <a href="{{ route('admin.menu.subscription-packages.create') }}" class="group relative overflow-hidden bg-gradient-to-br from-purple-600 via-fuchsia-500 to-pink-500 rounded-2xl shadow-xl p-6 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 animate-slide-in" style="animation-delay: 0.3s">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500"></div>
        <div class="relative z-10">
            <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center mb-4 group-hover:rotate-12 transition-transform">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                </svg>
            </div>
            <h3 class="text-white font-bold text-lg mb-2">New Package</h3>
            <p class="text-purple-100 text-sm">Create subscription package</p>
            <div class="mt-4 flex items-center text-white font-semibold text-sm group-hover:translate-x-2 transition-transform">
                <span>Add Now</span>
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </div>
        </div>
    </a>
</div>

<!-- Food Categories Section -->
<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden mb-6 animate-slide-in">
    <div class="bg-gradient-to-r from-orange-500 via-pink-500 via-purple-500 to-blue-500 px-6 py-5 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent animate-shimmer"></div>
        <div class="flex items-center justify-between relative z-10">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-white">Food Categories</h2>
                    <p class="text-blue-100 text-sm">Manage your food categories</p>
                </div>
            </div>
            <span class="px-4 py-2 bg-white/20 backdrop-blur-sm text-white font-bold rounded-lg">{{ $foodCategories->count() }} Categories</span>
        </div>
    </div>
    <div class="p-6">
        @if($foodCategories->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($foodCategories as $category)
                    <div class="group relative overflow-hidden bg-gradient-to-br from-orange-50 via-pink-50 to-purple-50 dark:from-slate-700 dark:to-slate-800 rounded-xl p-5 border-2 border-orange-200 dark:border-slate-600 hover:border-orange-400 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-orange-400 via-pink-400 to-purple-500 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <span class="px-3 py-1 bg-gradient-to-r from-orange-200 to-pink-200 dark:from-orange-900/50 dark:to-pink-900/50 text-orange-800 dark:text-orange-300 text-xs font-bold rounded-full">
                                {{ $category->food_items_count }} items
                            </span>
                        </div>
                        <h3 class="font-bold text-lg bg-gradient-to-r from-orange-600 to-pink-600 bg-clip-text text-transparent dark:text-white mb-2">{{ $category->name }}</h3>
                        @if($category->description)
                            <p class="text-sm text-slate-600 dark:text-slate-400 mb-4 line-clamp-2">{{ $category->description }}</p>
                        @endif
                        <div class="flex items-center space-x-3 pt-3 border-t border-orange-200 dark:border-slate-600">
                            <a href="{{ route('admin.menu.categories.edit', $category) }}" class="flex-1 inline-flex items-center justify-center space-x-2 px-4 py-2 bg-gradient-to-r from-orange-500 to-pink-500 hover:from-orange-600 hover:to-pink-600 text-white rounded-lg font-semibold transition-all transform hover:scale-105 shadow-md hover:shadow-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                <span>Edit</span>
                            </a>
                            <form action="{{ route('admin.menu.categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this category? This action cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-4 py-2 bg-gradient-to-r from-red-500 to-rose-600 hover:from-red-600 hover:to-rose-700 text-white rounded-lg font-semibold transition-all transform hover:scale-105 shadow-md hover:shadow-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <div class="w-20 h-20 bg-gradient-to-br from-blue-100 to-indigo-100 dark:from-blue-900/30 dark:to-indigo-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-blue-500 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </div>
                <p class="text-slate-600 dark:text-slate-400 font-semibold mb-2">No food categories found</p>
                <p class="text-sm text-slate-500 dark:text-slate-500 mb-4">Start by creating your first food category</p>
                <a href="{{ route('admin.menu.categories.create') }}" class="inline-flex items-center space-x-2 px-4 py-2 bg-gradient-to-r from-orange-500 to-pink-500 hover:from-orange-600 hover:to-pink-600 text-white rounded-lg font-semibold transition-all shadow-lg hover:shadow-xl transform hover:scale-105">
                    <span>Create Category</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Kitchen Product Categories Section -->
<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden mb-6 animate-slide-in" style="animation-delay: 0.2s">
    <div class="bg-gradient-to-r from-cyan-400 via-blue-500 via-indigo-500 to-purple-600 px-6 py-5 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent animate-shimmer"></div>
        <div class="flex items-center justify-between relative z-10">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-white">Kitchen Product Categories</h2>
                    <p class="text-teal-100 text-sm">Manage your kitchen product categories</p>
                </div>
            </div>
            <span class="px-4 py-2 bg-white/20 backdrop-blur-sm text-white font-bold rounded-lg">{{ $kitchenCategories->count() }} Categories</span>
        </div>
    </div>
    <div class="p-6">
        @if($kitchenCategories->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($kitchenCategories as $category)
                    <div class="group relative overflow-hidden bg-gradient-to-br from-cyan-50 via-blue-50 to-indigo-50 dark:from-slate-700 dark:to-slate-800 rounded-xl p-5 border-2 border-cyan-200 dark:border-slate-600 hover:border-cyan-400 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-cyan-400 via-blue-400 to-indigo-500 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <span class="px-3 py-1 bg-gradient-to-r from-cyan-200 to-blue-200 dark:from-cyan-900/50 dark:to-blue-900/50 text-cyan-800 dark:text-cyan-300 text-xs font-bold rounded-full">
                                {{ $category->kitchen_products_count }} items
                            </span>
                        </div>
                        <h3 class="font-bold text-lg bg-gradient-to-r from-cyan-600 to-indigo-600 bg-clip-text text-transparent dark:text-white mb-2">{{ $category->name }}</h3>
                        @if($category->description)
                            <p class="text-sm text-slate-600 dark:text-slate-400 mb-4 line-clamp-2">{{ $category->description }}</p>
                        @endif
                        <div class="flex items-center space-x-3 pt-3 border-t border-cyan-200 dark:border-slate-600">
                            <a href="{{ route('admin.menu.categories.edit', $category) }}" class="flex-1 inline-flex items-center justify-center space-x-2 px-4 py-2 bg-gradient-to-r from-cyan-500 to-indigo-500 hover:from-cyan-600 hover:to-indigo-600 text-white rounded-lg font-semibold transition-all transform hover:scale-105 shadow-md hover:shadow-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                <span>Edit</span>
                            </a>
                            <form action="{{ route('admin.menu.categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this category? This action cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-4 py-2 bg-gradient-to-r from-red-500 to-rose-600 hover:from-red-600 hover:to-rose-700 text-white rounded-lg font-semibold transition-all transform hover:scale-105 shadow-md hover:shadow-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <div class="w-20 h-20 bg-gradient-to-br from-teal-100 to-cyan-100 dark:from-teal-900/30 dark:to-cyan-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-teal-500 dark:text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </div>
                <p class="text-slate-600 dark:text-slate-400 font-semibold mb-2">No kitchen categories found</p>
                <p class="text-sm text-slate-500 dark:text-slate-500 mb-4">Start by creating your first kitchen category</p>
                <a href="{{ route('admin.menu.categories.create') }}" class="inline-flex items-center space-x-2 px-4 py-2 bg-gradient-to-r from-cyan-500 to-indigo-500 hover:from-cyan-600 hover:to-indigo-600 text-white rounded-lg font-semibold transition-all shadow-lg hover:shadow-xl transform hover:scale-105">
                    <span>Create Category</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Subscription Packages Section -->
<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden animate-slide-in" style="animation-delay: 0.4s">
    <div class="bg-gradient-to-r from-purple-600 via-fuchsia-500 via-pink-500 to-rose-500 px-6 py-5 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent animate-shimmer"></div>
        <div class="flex items-center justify-between relative z-10">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-white">Subscription Packages</h2>
                    <p class="text-purple-100 text-sm">Manage your subscription packages</p>
                </div>
            </div>
            <span class="px-4 py-2 bg-white/20 backdrop-blur-sm text-white font-bold rounded-lg">{{ $subscriptionPackages->count() }} Packages</span>
        </div>
    </div>
    <div class="p-6">
        @if($subscriptionPackages->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($subscriptionPackages as $package)
                    <div class="group relative overflow-hidden bg-gradient-to-br from-purple-50 via-fuchsia-50 via-pink-50 to-rose-50 dark:from-slate-700 dark:to-slate-800 rounded-xl p-6 border-2 border-purple-200 dark:border-slate-600 hover:border-fuchsia-400 dark:hover:border-fuchsia-500 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                        <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-purple-400/30 via-fuchsia-400/30 to-pink-400/30 rounded-full -mr-10 -mt-10 group-hover:scale-150 transition-transform duration-500"></div>
                        <div class="relative z-10">
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-14 h-14 bg-gradient-to-br from-purple-500 via-fuchsia-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                    </svg>
                                </div>
                                <span class="px-3 py-1 bg-purple-100 dark:bg-purple-900/50 text-purple-800 dark:text-purple-300 text-xs font-bold rounded-full uppercase">
                                    {{ $package->duration_type }}
                                </span>
                            </div>
                            <h3 class="font-bold text-xl text-slate-900 dark:text-white mb-3">{{ $package->name }}</h3>
                            <div class="mb-4">
                                <p class="text-3xl font-bold bg-gradient-to-r from-purple-600 via-fuchsia-600 to-pink-600 bg-clip-text text-transparent mb-1">
                                    TZS {{ number_format($package->price, 2) }}
                                </p>
                                <p class="text-sm text-slate-600 dark:text-slate-400">Per {{ $package->duration_type }}</p>
                            </div>
                            @if($package->description)
                                <p class="text-sm text-slate-600 dark:text-slate-400 mb-5 line-clamp-2">{{ $package->description }}</p>
                            @endif
                            <div class="flex items-center space-x-3 pt-4 border-t border-purple-200 dark:border-slate-600">
                                <a href="{{ route('admin.menu.subscription-packages.edit', $package) }}" class="flex-1 inline-flex items-center justify-center space-x-2 px-4 py-2 bg-gradient-to-r from-purple-600 via-fuchsia-600 to-pink-600 hover:from-purple-700 hover:via-fuchsia-700 hover:to-pink-700 text-white rounded-lg font-semibold transition-all transform hover:scale-105 shadow-md hover:shadow-xl">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    <span>Edit</span>
                                </a>
                                <form action="{{ route('admin.menu.subscription-packages.destroy', $package) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Are you sure you want to delete this package?')" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg font-semibold transition-all transform hover:scale-105 shadow-md hover:shadow-lg">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <div class="w-20 h-20 bg-gradient-to-br from-purple-100 to-pink-100 dark:from-purple-900/30 dark:to-pink-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-purple-500 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </div>
                <p class="text-slate-600 dark:text-slate-400 font-semibold mb-2">No subscription packages found</p>
                <p class="text-sm text-slate-500 dark:text-slate-500 mb-4">Start by creating your first subscription package</p>
                <a href="{{ route('admin.menu.subscription-packages.create') }}" class="inline-flex items-center space-x-2 px-4 py-2 bg-gradient-to-r from-purple-600 via-fuchsia-600 to-pink-600 hover:from-purple-700 hover:via-fuchsia-700 hover:to-pink-700 text-white rounded-lg font-semibold transition-all shadow-lg hover:shadow-2xl transform hover:scale-105">
                    <span>Create Package</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </a>
            </div>
        @endif
    </div>
</div>

<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    @keyframes shimmer {
        0% {
            transform: translateX(-100%);
        }
        100% {
            transform: translateX(100%);
        }
    }

    .animate-shimmer {
        animation: shimmer 3s infinite;
    }

    /* Vibrant Colorful Scrollbar for all elements */
    * {
        scrollbar-width: thin;
        scrollbar-color: #f97316 #f0f0f0;
    }

    *::-webkit-scrollbar {
        width: 12px;
        height: 12px;
    }

    *::-webkit-scrollbar-track {
        background: linear-gradient(to bottom, #fef3c7, #fed7aa, #fdba74);
        border-radius: 10px;
        box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.1);
    }

    *::-webkit-scrollbar-thumb {
        background: linear-gradient(to bottom, #f97316, #ea580c, #dc2626, #b91c1c);
        border-radius: 10px;
        border: 2px solid #fed7aa;
        box-shadow: 0 2px 6px rgba(249, 115, 22, 0.4);
    }

    *::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(to bottom, #ea580c, #dc2626, #b91c1c, #991b1b);
        box-shadow: 0 4px 8px rgba(249, 115, 22, 0.6);
        transform: scale(1.1);
    }

    *::-webkit-scrollbar-corner {
        background: linear-gradient(to bottom right, #fef3c7, #fed7aa);
    }

    /* Dark mode scrollbar */
    .dark *::-webkit-scrollbar-track {
        background: linear-gradient(to bottom, #1e1b4b, #312e81, #3730a3);
        box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.3);
    }

    .dark *::-webkit-scrollbar-thumb {
        background: linear-gradient(to bottom, #a855f7, #9333ea, #7c3aed, #6366f1);
        border: 2px solid #6366f1;
        box-shadow: 0 2px 6px rgba(168, 85, 247, 0.4);
    }

    .dark *::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(to bottom, #9333ea, #7c3aed, #6366f1, #4f46e5);
        box-shadow: 0 4px 8px rgba(168, 85, 247, 0.6);
    }

    .dark *::-webkit-scrollbar-corner {
        background: linear-gradient(to bottom right, #1e1b4b, #312e81);
    }

    /* Main content scrollable area */
    main {
        scrollbar-width: thin;
        scrollbar-color: #f97316 #ffffff;
    }

    main::-webkit-scrollbar {
        width: 14px;
    }

    main::-webkit-scrollbar-track {
        background: linear-gradient(to right, #fff7ed, #ffedd5, #fed7aa);
        border-radius: 10px;
    }

    main::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #f97316 0%, #ea580c 25%, #dc2626 50%, #b91c1c 75%, #991b1b 100%);
        border-radius: 10px;
        border: 2px solid #fed7aa;
        box-shadow: 0 0 10px rgba(249, 115, 22, 0.5);
    }

    main::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, #ea580c 0%, #dc2626 25%, #b91c1c 50%, #991b1b 75%, #7f1d1d 100%);
        box-shadow: 0 0 15px rgba(249, 115, 22, 0.8);
    }
</style>
@endsection
