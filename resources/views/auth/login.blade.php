@extends('layouts.app')

@section('title', 'Ingia — Monana Platform')

@section('content')
<div class="min-h-[calc(100vh-8rem)] flex items-center justify-center py-6 sm:py-10 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl w-full">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-0 lg:gap-0 overflow-hidden rounded-2xl sm:rounded-3xl shadow-2xl">
            <!-- Left Panel — Brand Illustration (hidden on mobile) -->
            <div class="hidden lg:flex flex-col justify-between relative overflow-hidden bg-gradient-to-br from-[#1a3a5c] via-[#1a2744] to-[#0f1a2e] p-8 lg:p-10">
                <div class="absolute inset-0 opacity-[0.07]" style="background-image:url('https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=600&q=30');background-size:cover;background-position:center"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-[#0f1a2e] via-transparent to-transparent"></div>
                <div class="relative z-10">
                    <a href="{{ route('home') }}">
                        <img src="{{ asset('images/lg.jpeg') }}" alt="Monana Platform" class="h-10 w-auto mb-8 rounded-lg">
                    </a>
                    <h2 class="text-2xl lg:text-3xl font-black text-white leading-tight mb-3">
                        Karibu<br>Monana Platform
                    </h2>
                    <p class="text-sm text-blue-200/70 leading-relaxed max-w-xs">Agiza chakula kitamu, bidhaa za jikoni, na mengi zaidi — yote kwa kubonyeza tu.</p>
                </div>
                <div class="relative z-10 space-y-4 mt-8">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 bg-white/10 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4.5 h-4.5 text-[#00d4aa]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div><p class="text-sm font-medium text-white">Monana Food</p><p class="text-xs text-blue-200/50">Milo tayari — Asubuhi, Mchana, Jioni</p></div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 bg-white/10 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4.5 h-4.5 text-[#ff6b35]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                        </div>
                        <div><p class="text-sm font-medium text-white">Monana Market</p><p class="text-xs text-blue-200/50">Bidhaa za jikoni hadi mlangoni</p></div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 bg-white/10 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4.5 h-4.5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <div><p class="text-sm font-medium text-white">Delivery Bure</p><p class="text-xs text-blue-200/50">Tunafikisha mlangoni kwako</p></div>
                    </div>
                </div>
            </div>

            <!-- Right Panel — Login Form -->
            <div class="bg-[#1a1a1a] border border-[#333] lg:border-l-0 p-6 sm:p-8 lg:p-10">
                <!-- Mobile Logo -->
                <div class="text-center lg:hidden mb-6">
                    <a href="{{ route('home') }}" class="inline-block mb-3">
                        <img src="{{ asset('images/lg.jpeg') }}" alt="Monana Platform" class="h-12 w-auto mx-auto rounded-lg">
                    </a>
                    <h2 class="text-2xl sm:text-3xl font-black text-white mb-1">Karibu Tena!</h2>
                    <p class="text-sm text-[#9ca3af]">Ingia kwenye akaunti yako</p>
                </div>

                <!-- Desktop heading -->
                <div class="hidden lg:block mb-8">
                    <h2 class="text-2xl font-black text-white mb-1">Karibu Tena!</h2>
                    <p class="text-sm text-[#9ca3af]">Ingia kwenye akaunti yako ya Monana Platform</p>
                </div>

                <form action="{{ route('login') }}" method="POST" class="space-y-5" x-data="{ submitting: false, showPass: false }" @submit="submitting = true">
                    @csrf

                    @if(session('error'))
                        <div class="bg-red-500/15 border border-red-500/30 text-red-400 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ session('error') }}
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="bg-green-500/15 border border-green-500/30 text-green-400 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            {{ session('success') }}
                        </div>
                    @endif

                    <div>
                        <label for="login" class="block text-sm font-semibold text-white mb-2">
                            Email au Namba ya Simu <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-[#555]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                            </div>
                            <input type="text" name="login" id="login" value="{{ old('login') }}" required autofocus
                                class="w-full pl-11 pr-4 py-3 bg-[#242424] border border-[#333] rounded-xl text-white placeholder-[#555] focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 transition-all text-sm"
                                placeholder="email@mfano.com au 0712345678">
                        </div>
                        @error('login')
                            <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-semibold text-white mb-2">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-[#555]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            </div>
                            <input :type="showPass ? 'text' : 'password'" name="password" id="password" required
                                class="w-full pl-11 pr-12 py-3 bg-[#242424] border border-[#333] rounded-xl text-white placeholder-[#555] focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 transition-all text-sm"
                                placeholder="••••••••">
                            <button type="button" @click="showPass = !showPass" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-[#555] hover:text-white transition-colors">
                                <svg x-show="!showPass" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                <svg x-show="showPass" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input type="checkbox" name="remember" id="remember" class="h-4 w-4 text-blue-600 focus:ring-blue-500 bg-[#242424] border-[#333] rounded">
                            <label for="remember" class="ml-2 block text-sm text-[#9ca3af]">Nikumbuke</label>
                        </div>
                        <a href="#" class="text-sm text-blue-400 hover:text-blue-300 transition-colors">Umesahau?</a>
                    </div>

                    <button type="submit" :disabled="submitting"
                        :class="submitting ? 'opacity-60 cursor-not-allowed' : 'hover:bg-blue-700 hover:scale-[1.01] active:scale-[0.99]'"
                        class="w-full bg-blue-600 text-white py-3 rounded-xl font-bold text-sm sm:text-base transition-all transform shadow-lg shadow-blue-600/20 inline-flex items-center justify-center gap-2">
                        <svg x-show="submitting" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <span x-text="submitting ? 'Inaingia...' : 'Ingia'">Ingia</span>
                    </button>
                </form>

                <div class="mt-6 pt-6 border-t border-[#333] text-center">
                    <p class="text-sm text-[#9ca3af]">
                        Huna akaunti?
                        <a href="{{ route('register') }}" class="text-blue-400 hover:text-blue-300 font-bold ml-1 transition-colors">Jisajili sasa</a>
                    </p>
                </div>

                <!-- Back to Home (mobile) -->
                <div class="mt-4 text-center lg:hidden">
                    <a href="{{ route('home') }}" class="inline-flex items-center text-xs text-[#6b6b6b] hover:text-white transition-colors">
                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        Rudi nyumbani
                    </a>
                </div>
            </div>
        </div>

        <!-- Back to Home (desktop) -->
        <div class="text-center mt-4 hidden lg:block">
            <a href="{{ route('home') }}" class="inline-flex items-center text-sm text-[#6b6b6b] hover:text-white transition-colors">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                Rudi nyumbani
            </a>
        </div>
    </div>
</div>
@endsection
