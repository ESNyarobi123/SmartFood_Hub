@extends('admin.layout')

@section('title', 'Add Menu Item')
@section('subtitle', 'Create a new menu item for Monana Food')

@section('content')
<div class="max-w-2xl">
    <form action="{{ route('admin.cyber.menu.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="card p-6 space-y-6">
            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-white mb-2">Name *</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                    class="w-full px-4 py-3 bg-[#2d2d2d] border border-[#333] rounded-lg text-white placeholder-[#6b6b6b] focus:border-[#00d4aa] focus:ring-1 focus:ring-[#00d4aa] transition-colors"
                    placeholder="e.g., Chapati">
                @error('name')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-white mb-2">Description</label>
                <textarea name="description" id="description" rows="3"
                    class="w-full px-4 py-3 bg-[#2d2d2d] border border-[#333] rounded-lg text-white placeholder-[#6b6b6b] focus:border-[#00d4aa] focus:ring-1 focus:ring-[#00d4aa] transition-colors"
                    placeholder="Brief description of the item">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Price -->
            <div>
                <label for="price" class="block text-sm font-medium text-white mb-2">Price (TZS) *</label>
                <input type="number" name="price" id="price" value="{{ old('price') }}" required min="0" step="0.01"
                    class="w-full px-4 py-3 bg-[#2d2d2d] border border-[#333] rounded-lg text-white placeholder-[#6b6b6b] focus:border-[#00d4aa] focus:ring-1 focus:ring-[#00d4aa] transition-colors"
                    placeholder="e.g., 500">
                @error('price')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Meal Slot -->
            <div>
                <label class="block text-sm font-medium text-white mb-2">Availability</label>
                <div class="space-y-3">
                    <label class="flex items-center space-x-3 p-3 bg-[#2d2d2d] rounded-lg cursor-pointer hover:bg-[#333] transition-colors">
                        <input type="checkbox" name="available_all_slots" value="1" {{ old('available_all_slots', true) ? 'checked' : '' }}
                            class="w-4 h-4 text-[#00d4aa] bg-[#333] border-[#444] rounded focus:ring-[#00d4aa]"
                            onchange="toggleMealSlotSelect(this)">
                        <span class="text-sm text-white">Available for all meal slots</span>
                    </label>

                    <div id="meal_slot_container" class="{{ old('available_all_slots', true) ? 'hidden' : '' }}">
                        <select name="meal_slot_id" id="meal_slot_id"
                            class="w-full px-4 py-3 bg-[#2d2d2d] border border-[#333] rounded-lg text-white focus:border-[#00d4aa] focus:ring-1 focus:ring-[#00d4aa] transition-colors">
                            <option value="">Select a meal slot</option>
                            @foreach($mealSlots as $slot)
                                <option value="{{ $slot->id }}" {{ old('meal_slot_id') == $slot->id ? 'selected' : '' }}>
                                    {{ $slot->display_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Is Available -->
            <div>
                <label class="flex items-center space-x-3 p-3 bg-[#2d2d2d] rounded-lg cursor-pointer hover:bg-[#333] transition-colors">
                    <input type="checkbox" name="is_available" value="1" {{ old('is_available', true) ? 'checked' : '' }}
                        class="w-4 h-4 text-[#00d4aa] bg-[#333] border-[#444] rounded focus:ring-[#00d4aa]">
                    <span class="text-sm text-white">Item is available for ordering</span>
                </label>
            </div>

            <!-- Image -->
            <div>
                <label for="image" class="block text-sm font-medium text-white mb-2">Image</label>
                <input type="file" name="image" id="image" accept="image/*"
                    class="w-full px-4 py-3 bg-[#2d2d2d] border border-[#333] rounded-lg text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-[#00d4aa] file:text-black file:font-medium hover:file:bg-[#00b894] file:cursor-pointer">
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
            <button type="submit" class="px-6 py-3 bg-[#00d4aa] hover:bg-[#00b894] text-black font-medium rounded-lg transition-colors">
                Create Item
            </button>
            <a href="{{ route('admin.cyber.menu.index') }}" class="px-6 py-3 bg-[#333] hover:bg-[#444] text-white font-medium rounded-lg transition-colors">
                Cancel
            </a>
        </div>
    </form>
</div>

<script>
function toggleMealSlotSelect(checkbox) {
    const container = document.getElementById('meal_slot_container');
    if (checkbox.checked) {
        container.classList.add('hidden');
    } else {
        container.classList.remove('hidden');
    }
}

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
