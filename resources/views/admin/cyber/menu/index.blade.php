@extends('admin.layout')

@section('title', 'Menu Items')
@section('subtitle', 'Manage Monana Food menu items')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm text-[#a0a0a0]">{{ $menuItems->total() }} menu items</p>
        </div>
        <a href="{{ route('admin.cyber.menu.create') }}" class="inline-flex items-center px-4 py-2 bg-[#00d4aa] hover:bg-[#00b894] text-black font-medium rounded-lg transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add Menu Item
        </a>
    </div>

    <!-- Menu Items Table -->
    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-[#2d2d2d]">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-[#6b6b6b] uppercase">Item</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-[#6b6b6b] uppercase">Price</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-[#6b6b6b] uppercase">Meal Slot</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-[#6b6b6b] uppercase">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-[#6b6b6b] uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#333]">
                    @forelse($menuItems as $item)
                        <tr class="hover:bg-[#2d2d2d] transition-colors">
                            <td class="px-4 py-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-20 h-20 bg-[#333] rounded-lg flex items-center justify-center overflow-hidden border border-[#444] flex-shrink-0 shadow-md">
                                        @if($item->image)
                                            <img src="{{ asset('storage/' . $item->image) }}" 
                                                 alt="{{ $item->name }}" 
                                                 class="w-full h-full object-cover"
                                                 onerror="this.onerror=null; this.parentElement.innerHTML='<svg class=\'w-8 h-8 text-[#6b6b6b]\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z\'></path></svg>';">
                                        @else
                                            <svg class="w-8 h-8 text-[#6b6b6b]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-white">{{ $item->name }}</p>
                                        <p class="text-xs text-[#6b6b6b]">{{ Str::limit($item->description, 40) }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <p class="text-sm font-medium text-[#00d4aa]">TZS {{ number_format($item->price) }}</p>
                            </td>
                            <td class="px-4 py-4">
                                @if($item->available_all_slots)
                                    <span class="text-xs px-2 py-1 bg-blue-500/20 text-blue-500 rounded-full">All Slots</span>
                                @elseif($item->mealSlot)
                                    <span class="text-xs px-2 py-1 bg-[#333] text-[#a0a0a0] rounded-full">{{ $item->mealSlot->display_name }}</span>
                                @else
                                    <span class="text-xs text-[#6b6b6b]">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-4">
                                @if($item->is_available)
                                    <span class="text-xs px-2 py-1 bg-green-500/20 text-green-500 rounded-full">Available</span>
                                @else
                                    <span class="text-xs px-2 py-1 bg-red-500/20 text-red-500 rounded-full">Unavailable</span>
                                @endif
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('admin.cyber.menu.edit', $item) }}" class="p-2 hover:bg-[#333] rounded-lg transition-colors" title="Edit">
                                        <svg class="w-4 h-4 text-[#a0a0a0]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.cyber.menu.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this item?')">
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
                            <td colspan="5" class="px-4 py-12 text-center">
                                <svg class="w-12 h-12 mx-auto text-[#333] mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                                </svg>
                                <p class="text-[#6b6b6b]">No menu items yet</p>
                                <a href="{{ route('admin.cyber.menu.create') }}" class="mt-2 inline-block text-sm text-[#00d4aa] hover:underline">Add your first item</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($menuItems->hasPages())
            <div class="p-4 border-t border-[#333]">
                {{ $menuItems->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
