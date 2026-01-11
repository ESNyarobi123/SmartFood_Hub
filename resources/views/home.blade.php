@extends('layouts.app')

@section('title', 'Home - Vyakula na Bidhaa za Jikoni')

@section('content')
<!-- Hero Section - Clean Layout like Msosidrop -->
<div class="bg-white dark:bg-slate-900 min-h-[85vh] flex items-center py-16 lg:py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
            <!-- Left Content -->
            <div class="text-center lg:text-left space-y-8 lg:space-y-10 order-2 lg:order-1">
                <div class="space-y-6">
                    <!-- Main Headline -->
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-bold leading-tight text-slate-900 dark:text-slate-100">
                        Pata Vyakula
                        <span class="block text-blue-600 dark:text-blue-400">Vya Kupendeza</span>
                        <span class="block">na SmartFood Hub</span>
                    </h1>

                    <!-- Sub-headline/Tagline -->
                    <p class="text-lg sm:text-xl lg:text-2xl text-slate-600 dark:text-slate-400 leading-relaxed max-w-2xl mx-auto lg:mx-0">
                        Tunakuletea vyakula bora vya makao ya ndani haki mlangoni mwako!
                    </p>
                </div>

                <!-- Main Call to Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start pt-6">
                    <a href="#foods" class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-xl font-bold text-lg transition-all transform hover:scale-105 shadow-xl hover:shadow-2xl border-2 border-blue-500">
                        <svg class="mr-2 w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Angalia Menu
                        <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                    <a href="#packages" class="inline-flex items-center justify-center bg-white hover:bg-slate-50 text-blue-600 border-2 border-blue-600 px-8 py-4 rounded-xl font-bold text-lg transition-all transform hover:scale-105 shadow-xl hover:shadow-2xl dark:bg-slate-800 dark:border-blue-400 dark:text-blue-400 dark:hover:bg-slate-700">
                        <svg class="mr-2 w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Subscribe Sasa
                    </a>
                </div>

                <!-- Stats / Features -->
                <div class="grid grid-cols-3 gap-6 pt-8 border-t border-slate-200 dark:border-slate-700">
                    <div class="text-center lg:text-left">
                        <div class="text-3xl lg:text-4xl font-bold text-blue-600 dark:text-blue-400">100+</div>
                        <div class="text-sm text-slate-600 dark:text-slate-400 mt-1">Vyakula</div>
                    </div>
                    <div class="text-center lg:text-left">
                        <div class="text-3xl lg:text-4xl font-bold text-blue-600 dark:text-blue-400">50+</div>
                        <div class="text-sm text-slate-600 dark:text-slate-400 mt-1">Bidhaa</div>
                    </div>
                    <div class="text-center lg:text-left">
                        <div class="text-3xl lg:text-4xl font-bold text-blue-600 dark:text-blue-400">24/7</div>
                        <div class="text-sm text-slate-600 dark:text-slate-400 mt-1">Huduma</div>
                    </div>
                </div>
            </div>

            <!-- Right Image - Large Hero Image -->
            <div class="relative order-1 lg:order-2">
                <div class="relative group">
                    <!-- Main Image Container -->
                    <div class="relative rounded-2xl lg:rounded-3xl overflow-hidden shadow-2xl transform group-hover:scale-[1.02] transition-transform duration-500">
                        <img 
                            src="{{ asset('images/heromsosi.webp') }}" 
                            alt="Chakula Bora - Healthy Meals from SmartFood Hub" 
                            class="w-full h-[400px] sm:h-[500px] lg:h-[600px] xl:h-[700px] object-cover group-hover:scale-110 transition-transform duration-700"
                        >
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Features Section -->
<section id="features" class="bg-blue-50 dark:bg-slate-900 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl lg:text-4xl font-bold text-blue-900 dark:text-blue-100 mb-4">
                Kwa nini Chagua SmartFood Hub?
            </h2>
            <p class="text-lg text-slate-600 dark:text-slate-400 max-w-2xl mx-auto">
                Huduma bora za chakula na bidhaa za jikoni
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-lg hover:shadow-xl transition-shadow">
                <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mb-4">
                    <span class="text-3xl">🚚</span>
                </div>
                <h3 class="text-xl font-semibold text-blue-900 dark:text-blue-100 mb-2">Ufikiaji wa Haraka</h3>
                <p class="text-slate-600 dark:text-slate-400">Tunapakua chakula chako kwa haraka na kwa usalama</p>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-lg hover:shadow-xl transition-shadow">
                <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mb-4">
                    <span class="text-3xl">✨</span>
                </div>
                <h3 class="text-xl font-semibold text-blue-900 dark:text-blue-100 mb-2">Ubora wa Juu</h3>
                <p class="text-slate-600 dark:text-slate-400">Vyakula vya ubora wa juu vilivyopikwa kwa uangalifu</p>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-lg hover:shadow-xl transition-shadow">
                <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mb-4">
                    <span class="text-3xl">💳</span>
                </div>
                <h3 class="text-xl font-semibold text-blue-900 dark:text-blue-100 mb-2">Malipo Salama</h3>
                <p class="text-slate-600 dark:text-slate-400">Lipia kwa urahisi kwa kutumia Mobile Money</p>
            </div>
        </div>
    </div>
</section>

<!-- Vyakula vinavyopikwa Section -->
<section id="foods" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="text-center mb-12">
        <h2 class="text-4xl font-bold text-blue-900 dark:text-blue-100 mb-4">
            Vyakula vinavyopikwa
        </h2>
        <p class="text-lg text-slate-600 dark:text-slate-400">
            Chagua kutoka kwenye menu yetu ya vyakula vya kawaida
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($foodCategories as $category)
            @foreach($category->foodItems as $food)
                <div class="group bg-white dark:bg-slate-800 rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="relative h-64 bg-gradient-to-br from-blue-200 via-blue-100 to-blue-50 dark:from-slate-700 dark:via-slate-600 dark:to-slate-700 overflow-hidden">
                        @if($food->image)
                            <img src="{{ asset('storage/' . $food->image) }}" alt="{{ $food->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <div class="text-center">
                                    <span class="text-8xl opacity-50">🍽️</span>
                                </div>
                            </div>
                        @endif
                        <div class="absolute top-4 right-4">
                            @if($food->is_available)
                                <span class="bg-green-500 text-white px-3 py-1 rounded-full text-xs font-semibold">Inapatikana</span>
                            @else
                                <span class="bg-red-500 text-white px-3 py-1 rounded-full text-xs font-semibold">Haipatikani</span>
                            @endif
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-2xl font-bold text-blue-900 dark:text-blue-100 mb-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                            {{ $food->name }}
                        </h3>
                        <p class="text-slate-600 dark:text-slate-400 mb-4 line-clamp-2">
                            {{ $food->description ?: 'Chakula kitamu na cha afya' }}
                        </p>
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-3xl font-bold text-blue-700 dark:text-blue-300">
                                TZS {{ number_format($food->price, 2) }}
                            </span>
                        </div>
                        @auth
                            @if($food->is_available)
                                <form action="{{ route('orders.store') }}" method="POST" class="inline w-full">
                                    @csrf
                                    <input type="hidden" name="items[0][id]" value="{{ $food->id }}">
                                    <input type="hidden" name="items[0][type]" value="food">
                                    <input type="hidden" name="items[0][quantity]" value="1">
                                    <input type="hidden" name="delivery_address" value="{{ auth()->user()->address ?? '' }}">
                                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition-all transform hover:scale-105">
                                        Order Now
                                    </button>
                                </form>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center px-6 py-3 rounded-lg font-semibold transition-all transform hover:scale-105">
                                Login to Order
                            </a>
                        @endauth
                    </div>
                </div>
            @endforeach
        @empty
            <div class="col-span-3 text-center py-12">
                <p class="text-slate-600 dark:text-slate-400 text-lg">Hakuna vyakula vinavyopatikana kwa sasa.</p>
            </div>
        @endforelse
    </div>
</section>

<!-- Bidhaa za jikoni Section -->
<section class="bg-blue-50 dark:bg-slate-900 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-blue-900 dark:text-blue-100 mb-4">
                Bidhaa za jikoni
            </h2>
            <p class="text-lg text-slate-600 dark:text-slate-400">
                Pata bidhaa za jikoni za ubora wa juu
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($kitchenCategories as $category)
                @foreach($category->kitchenProducts as $product)
                    <div class="group bg-white dark:bg-slate-800 rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                        <div class="relative h-64 bg-gradient-to-br from-teal-200 via-green-100 to-emerald-50 dark:from-slate-700 dark:via-slate-600 dark:to-slate-700 overflow-hidden">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <div class="text-center">
                                        <span class="text-8xl opacity-50">🔪</span>
                                    </div>
                                </div>
                            @endif
                            <div class="absolute top-4 right-4">
                                @if($product->is_available)
                                    <span class="bg-green-500 text-white px-3 py-1 rounded-full text-xs font-semibold">Inapatikana</span>
                                @else
                                    <span class="bg-red-500 text-white px-3 py-1 rounded-full text-xs font-semibold">Haipatikani</span>
                                @endif
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="text-2xl font-bold text-blue-900 dark:text-blue-100 mb-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                {{ $product->name }}
                            </h3>
                            <p class="text-slate-600 dark:text-slate-400 mb-4 line-clamp-2">
                                {{ $product->description ?: 'Bidhaa za jikoni za ubora wa juu' }}
                            </p>
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-3xl font-bold text-blue-700 dark:text-blue-300">
                                    TZS {{ number_format($product->price, 2) }}
                                </span>
                            </div>
                            @auth
                                @if($product->is_available)
                                    <form action="{{ route('orders.store') }}" method="POST" class="inline w-full">
                                        @csrf
                                        <input type="hidden" name="items[0][id]" value="{{ $product->id }}">
                                        <input type="hidden" name="items[0][type]" value="kitchen">
                                        <input type="hidden" name="items[0][quantity]" value="1">
                                        <input type="hidden" name="delivery_address" value="{{ auth()->user()->address ?? '' }}">
                                        <button type="submit" class="w-full bg-teal-600 hover:bg-teal-700 text-white px-6 py-3 rounded-lg font-semibold transition-all transform hover:scale-105">
                                            Order Now
                                        </button>
                                    </form>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="block w-full bg-teal-600 hover:bg-teal-700 text-white text-center px-6 py-3 rounded-lg font-semibold transition-all transform hover:scale-105">
                                    Login to Order
                                </a>
                            @endauth
                        </div>
                    </div>
                @endforeach
            @empty
                <div class="col-span-3 text-center py-12">
                    <p class="text-slate-600 dark:text-slate-400 text-lg">Hakuna bidhaa za jikoni zinazopatikana kwa sasa.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Subscription packages Section -->
<section id="packages" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="text-center mb-12">
        <h2 class="text-4xl font-bold text-blue-900 dark:text-blue-100 mb-4">
            Subscription Packages
        </h2>
        <p class="text-lg text-slate-600 dark:text-slate-400">
            Jiandikishe kwa huduma yetu ya subscription na upate chakula kila siku
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($subscriptionPackages as $package)
            <div class="group relative bg-gradient-to-br from-blue-600 to-blue-800 dark:from-slate-800 dark:to-slate-900 rounded-2xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                <!-- Decorative Elements -->
                <div class="absolute top-0 right-0 w-32 h-32 bg-blue-400/20 rounded-full -mr-16 -mt-16"></div>
                <div class="absolute bottom-0 left-0 w-24 h-24 bg-blue-500/20 rounded-full -ml-12 -mb-12"></div>

                <div class="relative p-8 text-white">
                    <div class="mb-6">
                        <h3 class="text-3xl font-bold mb-2">{{ $package->name }}</h3>
                        <p class="text-blue-100 dark:text-blue-200">{{ $package->description ?: 'Package bora ya subscription' }}</p>
                    </div>

                    <div class="mb-6 space-y-3">
                        <div class="flex items-center text-blue-100 dark:text-blue-200">
                            <span class="mr-3">✓</span>
                            <span>Duration: {{ ucfirst($package->duration_type) }}</span>
                        </div>
                        <div class="flex items-center text-blue-100 dark:text-blue-200">
                            <span class="mr-3">✓</span>
                            <span>Meals per week: {{ $package->meals_per_week }}</span>
                        </div>
                        @if($package->delivery_days)
                            <div class="flex items-center text-blue-100 dark:text-blue-200">
                                <span class="mr-3">✓</span>
                                <span>Delivery: {{ count($package->delivery_days) }} days/week</span>
                            </div>
                        @endif
                    </div>

                    <div class="mb-8">
                        <div class="text-5xl font-bold mb-2">TZS {{ number_format($package->price, 0) }}</div>
                        <div class="text-blue-100 dark:text-blue-200 text-sm">per {{ $package->duration_type === 'weekly' ? 'week' : 'month' }}</div>
                    </div>

                    @auth
                        <a href="{{ route('payment.create', ['package' => $package->id]) }}" class="block w-full bg-white text-blue-600 hover:bg-blue-50 text-center px-6 py-4 rounded-lg font-bold text-lg transition-all transform hover:scale-105 shadow-lg">
                            Subscribe Now
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="block w-full bg-white text-blue-600 hover:bg-blue-50 text-center px-6 py-4 rounded-lg font-bold text-lg transition-all transform hover:scale-105 shadow-lg">
                            Login to Subscribe
                        </a>
                    @endauth
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-12">
                <p class="text-slate-600 dark:text-slate-400 text-lg">Hakuna subscription packages zinazopatikana kwa sasa.</p>
            </div>
        @endforelse
    </div>
</section>

<!-- CTA Section -->
<section class="bg-gradient-to-r from-blue-700 to-blue-900 dark:from-slate-800 dark:to-slate-900 text-white py-20">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-4xl lg:text-5xl font-bold mb-6">
            Tuanze Sasa?
        </h2>
        <p class="text-xl text-blue-100 dark:text-blue-200 mb-8">
            Jiandikishe sasa na upate chakula chemsha kila siku
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            @auth
                <a href="#packages" class="bg-white text-blue-700 hover:bg-blue-50 px-8 py-4 rounded-lg font-semibold text-lg transition-all transform hover:scale-105 shadow-lg">
                    Angalia Packages
                </a>
            @else
                <a href="{{ route('register') }}" class="bg-white text-blue-700 hover:bg-blue-50 px-8 py-4 rounded-lg font-semibold text-lg transition-all transform hover:scale-105 shadow-lg">
                    Jisajili Sasa
                </a>
                <a href="{{ route('login') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-8 py-4 rounded-lg font-semibold text-lg transition-all transform hover:scale-105 shadow-lg border-2 border-blue-400">
                    Ingia
                </a>
            @endauth
        </div>
    </div>
</section>
@endsection
