@extends('admin.layout')

@section('title', 'Add Product')
@section('subtitle', 'Create a new kitchen product')

@section('content')
<div class="max-w-2xl">
    <form action="{{ route('admin.food.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="card p-6 space-y-6">
            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-white mb-2">Name *</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                    class="w-full px-4 py-3 bg-[#2d2d2d] border border-[#333] rounded-lg text-white placeholder-[#6b6b6b] focus:border-[#ff6b35] focus:ring-1 focus:ring-[#ff6b35] transition-colors"
                    placeholder="e.g., Mchele">
                @error('name')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-white mb-2">Description</label>
                <textarea name="description" id="description" rows="3"
                    class="w-full px-4 py-3 bg-[#2d2d2d] border border-[#333] rounded-lg text-white placeholder-[#6b6b6b] focus:border-[#ff6b35] focus:ring-1 focus:ring-[#ff6b35] transition-colors"
                    placeholder="Brief description">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Price & Unit -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="price" class="block text-sm font-medium text-white mb-2">Price (TZS) *</label>
                    <input type="number" name="price" id="price" value="{{ old('price') }}" required min="0" step="0.01"
                        class="w-full px-4 py-3 bg-[#2d2d2d] border border-[#333] rounded-lg text-white placeholder-[#6b6b6b] focus:border-[#ff6b35] focus:ring-1 focus:ring-[#ff6b35] transition-colors"
                        placeholder="e.g., 3000">
                    @error('price')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="unit" class="block text-sm font-medium text-white mb-2">Unit *</label>
                    <select name="unit" id="unit" required
                        class="w-full px-4 py-3 bg-[#2d2d2d] border border-[#333] rounded-lg text-white focus:border-[#ff6b35] focus:ring-1 focus:ring-[#ff6b35] transition-colors">
                        <option value="kg" {{ old('unit') == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                        <option value="g" {{ old('unit') == 'g' ? 'selected' : '' }}>Gram (g)</option>
                        <option value="pieces" {{ old('unit') == 'pieces' ? 'selected' : '' }}>Pieces</option>
                        <option value="liters" {{ old('unit') == 'liters' ? 'selected' : '' }}>Liters</option>
                        <option value="ml" {{ old('unit') == 'ml' ? 'selected' : '' }}>Milliliters (ml)</option>
                        <option value="pack" {{ old('unit') == 'pack' ? 'selected' : '' }}>Pack</option>
                    </select>
                    @error('unit')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Stock -->
            <div>
                <label for="stock_quantity" class="block text-sm font-medium text-white mb-2">Stock Quantity *</label>
                <input type="number" name="stock_quantity" id="stock_quantity" value="{{ old('stock_quantity', 0) }}" required min="0"
                    class="w-full px-4 py-3 bg-[#2d2d2d] border border-[#333] rounded-lg text-white placeholder-[#6b6b6b] focus:border-[#ff6b35] focus:ring-1 focus:ring-[#ff6b35] transition-colors">
                @error('stock_quantity')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Options -->
            <div class="space-y-3">
                <label class="flex items-center space-x-3 p-3 bg-[#2d2d2d] rounded-lg cursor-pointer hover:bg-[#333] transition-colors">
                    <input type="checkbox" name="is_available" value="1" {{ old('is_available', true) ? 'checked' : '' }}
                        class="w-4 h-4 text-[#ff6b35] bg-[#333] border-[#444] rounded focus:ring-[#ff6b35]">
                    <span class="text-sm text-white">Product is available</span>
                </label>
                <label class="flex items-center space-x-3 p-3 bg-[#2d2d2d] rounded-lg cursor-pointer hover:bg-[#333] transition-colors">
                    <input type="checkbox" name="can_be_customized" value="1" {{ old('can_be_customized', true) ? 'checked' : '' }}
                        class="w-4 h-4 text-[#ff6b35] bg-[#333] border-[#444] rounded focus:ring-[#ff6b35]">
                    <span class="text-sm text-white">Can be used in package customization</span>
                </label>
            </div>

            <!-- Image -->
            <div>
                <label for="image" class="block text-sm font-medium text-white mb-2">Image</label>
                <input type="file" name="image" id="image" accept="image/*"
                    class="w-full px-4 py-3 bg-[#2d2d2d] border border-[#333] rounded-lg text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-[#ff6b35] file:text-white file:font-medium hover:file:bg-[#e55a2b] file:cursor-pointer">
                <p class="mt-1 text-xs text-[#6b6b6b]">Max 2MB. JPG, PNG, or GIF.</p>
                @error('image')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror

                <!-- Image Preview -->
                <div id="imagePreviewContainer" class="mt-4 hidden">
                    <label class="block text-sm font-medium text-white mb-2">Image Preview</label>
                    <div class="inline-block p-4 bg-[#2d2d2d] border border-[#333] rounded-xl shadow-lg">
                        <img id="imagePreview" src="" alt="Preview" class="w-64 h-64 object-cover rounded-lg border border-[#444]">
                    </div>
                    <div class="mt-2">
                        <p class="text-xs text-[#6b6b6b]">File path:</p>
                        <p id="imagePath" class="text-xs text-[#a0a0a0] font-mono break-all mt-1"></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center space-x-4">
            <button type="submit" class="px-6 py-3 bg-[#ff6b35] hover:bg-[#e55a2b] text-white font-medium rounded-lg transition-colors">
                Create Product
            </button>
            <a href="{{ route('admin.food.products.index') }}" class="px-6 py-3 bg-[#333] hover:bg-[#444] text-white font-medium rounded-lg transition-colors">
                Cancel
            </a>
        </div>
    </form>
</div>

<script>
// Image preview functionality
document.getElementById('image')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    const previewContainer = document.getElementById('imagePreviewContainer');
    const preview = document.getElementById('imagePreview');
    const pathDisplay = document.getElementById('imagePath');

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            previewContainer.classList.remove('hidden');
            pathDisplay.textContent = file.name;
        };
        reader.readAsDataURL(file);
    } else {
        previewContainer.classList.add('hidden');
    }
});
</script>
@endsection
