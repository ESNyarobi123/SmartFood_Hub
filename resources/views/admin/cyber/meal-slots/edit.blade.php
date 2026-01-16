@extends('admin.layout')

@section('title', 'Edit Meal Slot')
@section('subtitle', 'Update {{ $mealSlot->display_name }} timing')

@section('content')
<div class="max-w-xl">
    <form action="{{ route('admin.cyber.meal-slots.update', $mealSlot) }}" method="POST" class="space-y-6">
        @csrf
        @method('PATCH')

        <div class="card p-6 space-y-6">
            <!-- Display Name -->
            <div>
                <label for="display_name" class="block text-sm font-medium text-white mb-2">Display Name *</label>
                <input type="text" name="display_name" id="display_name" value="{{ old('display_name', $mealSlot->display_name) }}" required
                    class="w-full px-4 py-3 bg-[#2d2d2d] border border-[#333] rounded-lg text-white placeholder-[#6b6b6b] focus:border-[#00d4aa] focus:ring-1 focus:ring-[#00d4aa] transition-colors">
                @error('display_name')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Order Start Time -->
            <div>
                <label for="order_start_time" class="block text-sm font-medium text-white mb-2">Order Start Time *</label>
                <input type="time" name="order_start_time" id="order_start_time" 
                    value="{{ old('order_start_time', \Carbon\Carbon::parse($mealSlot->order_start_time)->format('H:i')) }}" required
                    class="w-full px-4 py-3 bg-[#2d2d2d] border border-[#333] rounded-lg text-white focus:border-[#00d4aa] focus:ring-1 focus:ring-[#00d4aa] transition-colors">
                @error('order_start_time')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Order End Time -->
            <div>
                <label for="order_end_time" class="block text-sm font-medium text-white mb-2">Order End Time (Cutoff) *</label>
                <input type="time" name="order_end_time" id="order_end_time" 
                    value="{{ old('order_end_time', \Carbon\Carbon::parse($mealSlot->order_end_time)->format('H:i')) }}" required
                    class="w-full px-4 py-3 bg-[#2d2d2d] border border-[#333] rounded-lg text-white focus:border-[#00d4aa] focus:ring-1 focus:ring-[#00d4aa] transition-colors">
                @error('order_end_time')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Delivery Time Label -->
            <div>
                <label for="delivery_time_label" class="block text-sm font-medium text-white mb-2">Delivery Time Label *</label>
                <input type="text" name="delivery_time_label" id="delivery_time_label" 
                    value="{{ old('delivery_time_label', $mealSlot->delivery_time_label) }}" required
                    class="w-full px-4 py-3 bg-[#2d2d2d] border border-[#333] rounded-lg text-white placeholder-[#6b6b6b] focus:border-[#00d4aa] focus:ring-1 focus:ring-[#00d4aa] transition-colors"
                    placeholder="e.g., Kesho Asubuhi (6:00 - 9:00 AM)">
                @error('delivery_time_label')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-white mb-2">Description</label>
                <textarea name="description" id="description" rows="3"
                    class="w-full px-4 py-3 bg-[#2d2d2d] border border-[#333] rounded-lg text-white placeholder-[#6b6b6b] focus:border-[#00d4aa] focus:ring-1 focus:ring-[#00d4aa] transition-colors"
                    placeholder="Brief description shown to customers">{{ old('description', $mealSlot->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center space-x-4">
            <button type="submit" class="px-6 py-3 bg-[#00d4aa] hover:bg-[#00b894] text-black font-medium rounded-lg transition-colors">
                Update Slot
            </button>
            <a href="{{ route('admin.cyber.meal-slots.index') }}" class="px-6 py-3 bg-[#333] hover:bg-[#444] text-white font-medium rounded-lg transition-colors">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
