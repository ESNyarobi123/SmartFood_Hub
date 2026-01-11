@extends('admin.layout')

@section('title', 'Create Category')

@section('content')
<div class="mb-8 animate-slide-in">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-4xl font-bold bg-gradient-to-r from-orange-500 via-pink-500 via-purple-500 via-blue-500 to-cyan-500 bg-clip-text text-transparent mb-2">Create Category</h1>
            <p class="text-slate-600 dark:text-slate-400">Add a new food or kitchen category</p>
        </div>
        <a href="{{ route('admin.menu.index') }}" class="mt-4 md:mt-0 px-4 py-2 bg-gradient-to-r from-slate-500 to-gray-600 hover:from-slate-600 hover:to-gray-700 text-white rounded-xl font-semibold transition-all transform hover:scale-105 shadow-lg hover:shadow-xl flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <span>Back</span>
        </a>
    </div>
</div>

<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
    <div class="bg-gradient-to-r from-orange-500 via-pink-500 via-purple-500 to-blue-500 px-6 py-5 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent animate-shimmer"></div>
        <h2 class="text-2xl font-bold text-white relative z-10">Category Information</h2>
    </div>

    <form action="{{ route('admin.menu.categories.store') }}" method="POST" class="p-6">
        @csrf

        <div class="mb-6">
            <label for="name" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                Category Name <span class="text-red-500">*</span>
            </label>
            <input
                type="text"
                name="name"
                id="name"
                value="{{ old('name') }}"
                required
                class="w-full px-4 py-3 border-2 border-slate-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all"
                placeholder="Enter category name"
            >
            @error('name')
                <p class="text-red-600 dark:text-red-400 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="description" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                Description
            </label>
            <textarea
                name="description"
                id="description"
                rows="4"
                class="w-full px-4 py-3 border-2 border-slate-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all resize-none"
                placeholder="Enter category description (optional)"
            >{{ old('description') }}</textarea>
            @error('description')
                <p class="text-red-600 dark:text-red-400 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="type" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                Category Type <span class="text-red-500">*</span>
            </label>
            <select
                name="type"
                id="type"
                required
                class="w-full px-4 py-3 border-2 border-slate-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all"
            >
                <option value="">Select category type</option>
                <option value="food" {{ old('type') === 'food' ? 'selected' : '' }}>Food Category</option>
                <option value="kitchen" {{ old('type') === 'kitchen' ? 'selected' : '' }}>Kitchen Product Category</option>
            </select>
            @error('type')
                <p class="text-red-600 dark:text-red-400 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label class="flex items-center space-x-3 cursor-pointer">
                <input
                    type="checkbox"
                    name="is_active"
                    value="1"
                    {{ old('is_active', true) ? 'checked' : '' }}
                    class="w-5 h-5 text-orange-500 border-2 border-slate-300 dark:border-slate-600 rounded focus:ring-2 focus:ring-orange-500"
                >
                <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">Active Category</span>
            </label>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 ml-8">Only active categories will be visible to users</p>
        </div>

        <div class="flex justify-end space-x-4 pt-4 border-t border-slate-200 dark:border-slate-700">
            <a href="{{ route('admin.menu.index') }}" class="px-6 py-3 border-2 border-slate-300 dark:border-slate-600 rounded-xl text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 font-semibold transition-all">
                Cancel
            </a>
            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-orange-500 via-pink-500 to-purple-600 hover:from-orange-600 hover:via-pink-600 hover:to-purple-700 text-white rounded-xl font-semibold transition-all transform hover:scale-105 shadow-lg hover:shadow-xl">
                Create Category
            </button>
        </div>
    </form>
</div>

<style>
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
</style>
@endsection
