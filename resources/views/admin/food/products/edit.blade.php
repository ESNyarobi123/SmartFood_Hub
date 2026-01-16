@extends('admin.layout')

@section('title', 'Edit Product')
@section('subtitle', 'Update product details')

@section('content')
<div class="max-w-2xl">
    <form action="{{ route('admin.food.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PATCH')

        <div class="card p-6 space-y-6">
            <!-- Current Image -->
            @if($product->image)
                <div>
                    <label class="block text-sm font-medium text-white mb-2">Current Image</label>
                    <div class="w-32 h-32 bg-[#333] rounded-lg overflow-hidden">
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                    </div>
                </div>
            @endif

            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-white mb-2">Name *</label>
                <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required
                    class="w-full px-4 py-3 bg-[#2d2d2d] border border-[#333] rounded-lg text-white placeholder-[#6b6b6b] focus:border-[#ff6b35] focus:ring-1 focus:ring-[#ff6b35] transition-colors">
                @error('name')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-white mb-2">Description</label>
                <textarea name="description" id="description" rows="3"
                    class="w-full px-4 py-3 bg-[#2d2d2d] border border-[#333] rounded-lg text-white placeholder-[#6b6b6b] focus:border-[#ff6b35] focus:ring-1 focus:ring-[#ff6b35] transition-colors">{{ old('description', $product->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Price & Unit -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="price" class="block text-sm font-medium text-white mb-2">Price (TZS) *</label>
                    <input type="number" name="price" id="price" value="{{ old('price', $product->price) }}" required min="0" step="0.01"
                        class="w-full px-4 py-3 bg-[#2d2d2d] border border-[#333] rounded-lg text-white placeholder-[#6b6b6b] focus:border-[#ff6b35] focus:ring-1 focus:ring-[#ff6b35] transition-colors">
                    @error('price')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="unit" class="block text-sm font-medium text-white mb-2">Unit *</label>
                    <select name="unit" id="unit" required
                        class="w-full px-4 py-3 bg-[#2d2d2d] border border-[#333] rounded-lg text-white focus:border-[#ff6b35] focus:ring-1 focus:ring-[#ff6b35] transition-colors">
                        <option value="kg" {{ old('unit', $product->unit) == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                        <option value="g" {{ old('unit', $product->unit) == 'g' ? 'selected' : '' }}>Gram (g)</option>
                        <option value="pieces" {{ old('unit', $product->unit) == 'pieces' ? 'selected' : '' }}>Pieces</option>
                        <option value="liters" {{ old('unit', $product->unit) == 'liters' ? 'selected' : '' }}>Liters</option>
                        <option value="ml" {{ old('unit', $product->unit) == 'ml' ? 'selected' : '' }}>Milliliters (ml)</option>
                        <option value="pack" {{ old('unit', $product->unit) == 'pack' ? 'selected' : '' }}>Pack</option>
                    </select>
                    @error('unit')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Stock -->
            <div>
                <label for="stock_quantity" class="block text-sm font-medium text-white mb-2">Stock Quantity *</label>
                <input type="number" name="stock_quantity" id="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}" required min="0"
                    class="w-full px-4 py-3 bg-[#2d2d2d] border border-[#333] rounded-lg text-white placeholder-[#6b6b6b] focus:border-[#ff6b35] focus:ring-1 focus:ring-[#ff6b35] transition-colors">
                @error('stock_quantity')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Options -->
            <div class="space-y-3">
                <label class="flex items-center space-x-3 p-3 bg-[#2d2d2d] rounded-lg cursor-pointer hover:bg-[#333] transition-colors">
                    <input type="checkbox" name="is_available" value="1" {{ old('is_available', $product->is_available) ? 'checked' : '' }}
                        class="w-4 h-4 text-[#ff6b35] bg-[#333] border-[#444] rounded focus:ring-[#ff6b35]">
                    <span class="text-sm text-white">Product is available</span>
                </label>
                <label class="flex items-center space-x-3 p-3 bg-[#2d2d2d] rounded-lg cursor-pointer hover:bg-[#333] transition-colors">
                    <input type="checkbox" name="can_be_customized" value="1" {{ old('can_be_customized', $product->can_be_customized) ? 'checked' : '' }}
                        class="w-4 h-4 text-[#ff6b35] bg-[#333] border-[#444] rounded focus:ring-[#ff6b35]">
                    <span class="text-sm text-white">Can be used in package customization</span>
                </label>
            </div>

            <!-- Image -->
            <div>
                <label for="image" class="block text-sm font-medium text-white mb-2">New Image</label>
                <input type="file" name="image" id="image" accept="image/*"
                    class="w-full px-4 py-3 bg-[#2d2d2d] border border-[#333] rounded-lg text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-[#ff6b35] file:text-white file:font-medium hover:file:bg-[#e55a2b] file:cursor-pointer">
                <p class="mt-1 text-xs text-[#6b6b6b]">Leave empty to keep current image</p>
                @error('image')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center space-x-4">
            <button type="submit" class="px-6 py-3 bg-[#ff6b35] hover:bg-[#e55a2b] text-white font-medium rounded-lg transition-colors">
                Update Product
            </button>
            <a href="{{ route('admin.food.products.index') }}" class="px-6 py-3 bg-[#333] hover:bg-[#444] text-white font-medium rounded-lg transition-colors">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
