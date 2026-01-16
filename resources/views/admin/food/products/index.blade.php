@extends('admin.layout')

@section('title', 'Products')
@section('subtitle', 'Manage kitchen products')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm text-[#a0a0a0]">{{ $products->total() }} products</p>
        </div>
        <a href="{{ route('admin.food.products.create') }}" class="inline-flex items-center px-4 py-2 bg-[#ff6b35] hover:bg-[#e55a2b] text-white font-medium rounded-lg transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add Product
        </a>
    </div>

    <!-- Filters -->
    <div class="card p-4">
        <form action="{{ route('admin.food.products.index') }}" method="GET" class="flex flex-wrap gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..."
                class="px-4 py-2 bg-[#2d2d2d] border border-[#333] rounded-lg text-white text-sm focus:border-[#ff6b35] placeholder-[#6b6b6b]">
            <select name="available" class="px-4 py-2 bg-[#2d2d2d] border border-[#333] rounded-lg text-white text-sm focus:border-[#ff6b35]">
                <option value="">All Status</option>
                <option value="yes" {{ request('available') == 'yes' ? 'selected' : '' }}>Available</option>
                <option value="no" {{ request('available') == 'no' ? 'selected' : '' }}>Unavailable</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-[#ff6b35] text-white font-medium rounded-lg hover:bg-[#e55a2b] transition-colors">
                Filter
            </button>
            @if(request()->hasAny(['search', 'available']))
                <a href="{{ route('admin.food.products.index') }}" class="px-4 py-2 bg-[#333] text-white font-medium rounded-lg hover:bg-[#444] transition-colors">
                    Clear
                </a>
            @endif
        </form>
    </div>

    <!-- Products Table -->
    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-[#2d2d2d]">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-[#6b6b6b] uppercase">Product</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-[#6b6b6b] uppercase">Price</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-[#6b6b6b] uppercase">Stock</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-[#6b6b6b] uppercase">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-[#6b6b6b] uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#333]">
                    @forelse($products as $product)
                        <tr class="hover:bg-[#2d2d2d] transition-colors">
                            <td class="px-4 py-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-12 h-12 bg-[#333] rounded-lg flex items-center justify-center overflow-hidden">
                                        @if($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                        @else
                                            <svg class="w-6 h-6 text-[#6b6b6b]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                            </svg>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-white">{{ $product->name }}</p>
                                        <p class="text-xs text-[#6b6b6b]">{{ $product->unit }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <p class="text-sm font-medium text-[#ff6b35]">TZS {{ number_format($product->price) }}</p>
                                <p class="text-xs text-[#6b6b6b]">per {{ $product->unit }}</p>
                            </td>
                            <td class="px-4 py-4">
                                <p class="text-sm font-medium {{ $product->stock_quantity < 10 ? 'text-red-500' : 'text-white' }}">
                                    {{ $product->stock_quantity }}
                                </p>
                            </td>
                            <td class="px-4 py-4">
                                @if($product->is_available)
                                    <span class="text-xs px-2 py-1 bg-green-500/20 text-green-500 rounded-full">Available</span>
                                @else
                                    <span class="text-xs px-2 py-1 bg-red-500/20 text-red-500 rounded-full">Unavailable</span>
                                @endif
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('admin.food.products.edit', $product) }}" class="p-2 hover:bg-[#333] rounded-lg transition-colors" title="Edit">
                                        <svg class="w-4 h-4 text-[#a0a0a0]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.food.products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 hover:bg-red-500/20 rounded-lg transition-colors" title="Delete">
                                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-12 text-center text-[#6b6b6b]">
                                No products found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
            <div class="p-4 border-t border-[#333]">
                {{ $products->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
