@extends('admin.layout')

@section('title', 'Packages')
@section('subtitle', 'Manage subscription packages')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm text-[#a0a0a0]">{{ $packages->total() }} packages</p>
        </div>
        <a href="{{ route('admin.food.packages.create') }}" class="inline-flex items-center px-4 py-2 bg-[#ff6b35] hover:bg-[#e55a2b] text-white font-medium rounded-lg transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Create Package
        </a>
    </div>

    <!-- Packages Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($packages as $package)
            <div class="card overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-white">{{ $package->name }}</h3>
                        @if($package->is_active)
                            <span class="text-xs px-2 py-1 bg-green-500/20 text-green-500 rounded-full">Active</span>
                        @else
                            <span class="text-xs px-2 py-1 bg-red-500/20 text-red-500 rounded-full">Inactive</span>
                        @endif
                    </div>

                    <p class="text-sm text-[#6b6b6b] mb-4">{{ Str::limit($package->description, 80) }}</p>

                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-[#6b6b6b]">Price:</span>
                            <span class="text-[#ff6b35] font-bold">TZS {{ number_format($package->base_price) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-[#6b6b6b]">Duration:</span>
                            <span class="text-white">{{ ucfirst($package->duration_type) }} ({{ $package->duration_days }} days)</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-[#6b6b6b]">Items:</span>
                            <span class="text-white">{{ $package->items_count }} products</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-[#6b6b6b]">Active Subs:</span>
                            <span class="text-white">{{ $package->active_subscriptions }}</span>
                        </div>
                    </div>
                </div>

                <div class="p-4 border-t border-[#333] bg-[#2d2d2d] flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('admin.food.packages.edit', $package) }}" class="px-3 py-1.5 bg-[#333] hover:bg-[#444] text-white text-xs font-medium rounded-lg transition-colors">
                            Edit
                        </a>
                        <a href="{{ route('admin.food.packages.rules', $package) }}" class="px-3 py-1.5 bg-blue-500/20 hover:bg-blue-500/30 text-blue-500 text-xs font-medium rounded-lg transition-colors">
                            Rules
                        </a>
                    </div>
                    @if($package->active_subscriptions == 0)
                        <form action="{{ route('admin.food.packages.destroy', $package) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1.5 hover:bg-red-500/20 rounded-lg transition-colors">
                                <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-3 card p-12 text-center">
                <svg class="w-12 h-12 mx-auto text-[#333] mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                </svg>
                <p class="text-[#6b6b6b]">No packages created yet</p>
                <a href="{{ route('admin.food.packages.create') }}" class="mt-2 inline-block text-sm text-[#ff6b35] hover:underline">Create your first package</a>
            </div>
        @endforelse
    </div>

    @if($packages->hasPages())
        <div class="mt-6">
            {{ $packages->links() }}
        </div>
    @endif
</div>
@endsection
