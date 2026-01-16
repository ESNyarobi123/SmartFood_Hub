@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="min-h-[calc(100vh-12rem)] flex items-center justify-center py-8 sm:py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-6 sm:space-y-8">
        <!-- Header with Logo -->
        <div class="text-center">
            <a href="{{ route('home') }}" class="inline-block mb-4">
                <img src="{{ asset('images/lg.jpeg') }}" alt="Monana Platform" class="h-12 sm:h-16 w-auto mx-auto">
            </a>
            <h2 class="text-3xl sm:text-4xl font-black text-white mb-2">
                Karibu Tena!
            </h2>
            <p class="text-sm sm:text-base text-[#a0a0a0]">
                Ingia kwenye akaunti yako ya Monana Platform
            </p>
        </div>

        <!-- Login Form -->
        <div class="card p-6 sm:p-8 rounded-2xl sm:rounded-3xl shadow-2xl">
            <form action="{{ route('login') }}" method="POST" class="space-y-5 sm:space-y-6">
                @csrf

                @if(session('error'))
                    <div class="bg-red-500/20 border border-red-500/50 text-red-400 px-4 py-3 rounded-lg text-sm">
                        {{ session('error') }}
                    </div>
                @endif

                @if(session('success'))
                    <div class="bg-green-500/20 border border-green-500/50 text-green-400 px-4 py-3 rounded-lg text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                <div>
                    <label for="email" class="block text-sm font-semibold text-white mb-2">
                        Email Address <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-[#6b6b6b]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                            </svg>
                        </div>
                        <input 
                            type="email" 
                            name="email" 
                            id="email" 
                            value="{{ old('email') }}" 
                            required 
                            autofocus
                            class="w-full pl-10 pr-4 py-3 bg-[#2d2d2d] border border-[#333] rounded-lg text-white placeholder-[#6b6b6b] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                            placeholder="example@email.com"
                        >
                    </div>
                    @error('email')
                        <p class="text-red-400 text-xs sm:text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-white mb-2">
                        Password <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-[#6b6b6b]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <input 
                            type="password" 
                            name="password" 
                            id="password" 
                            required
                            class="w-full pl-10 pr-4 py-3 bg-[#2d2d2d] border border-[#333] rounded-lg text-white placeholder-[#6b6b6b] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                            placeholder="••••••••"
                        >
                    </div>
                    @error('password')
                        <p class="text-red-400 text-xs sm:text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 sm:gap-0">
                    <div class="flex items-center">
                        <input 
                            type="checkbox" 
                            name="remember" 
                            id="remember" 
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 bg-[#2d2d2d] border-[#333] rounded"
                        >
                        <label for="remember" class="ml-2 block text-sm text-[#a0a0a0]">
                            Remember me
                        </label>
                    </div>

                    <a href="#" class="text-sm text-blue-400 hover:text-blue-300 transition-colors">
                        Forgot password?
                    </a>
                </div>

                <button 
                    type="submit" 
                    class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white py-3 sm:py-3.5 rounded-lg font-bold text-base sm:text-lg transition-all transform hover:scale-[1.02] active:scale-[0.98] shadow-lg hover:shadow-xl"
                >
                    Ingia
                </button>
            </form>

            <div class="mt-6 pt-6 border-t border-[#333] text-center">
                <p class="text-sm text-[#a0a0a0]">
                    Huna akaunti?
                    <a href="{{ route('register') }}" class="text-blue-400 hover:text-blue-300 font-semibold ml-1 transition-colors">
                        Jisajili sasa
                    </a>
                </p>
            </div>
        </div>

        <!-- Back to Home -->
        <div class="text-center">
            <a href="{{ route('home') }}" class="inline-flex items-center text-sm text-[#a0a0a0] hover:text-white transition-colors">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Rudi nyumbani
            </a>
        </div>
    </div>
</div>
@endsection
