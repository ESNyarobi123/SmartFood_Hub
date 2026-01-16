@extends('admin.layout')

@section('title', 'Edit Package')
@section('subtitle', 'Update package details')

@section('content')
<div class="max-w-4xl">
    <form action="{{ route('admin.food.packages.update', $package) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PATCH')

        <div class="card p-6 space-y-6">
            <!-- Basic Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-white mb-2">Package Name *</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $package->name) }}" required
                        class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-[#5c6b7f] focus:border-[#ff7b54] focus:ring-1 focus:ring-[#ff7b54] transition-colors"
                        placeholder="e.g., Weekly Essentials">
                    @error('name')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="base_price" class="block text-sm font-medium text-white mb-2">Base Price (TZS) *</label>
                    <input type="number" name="base_price" id="base_price" value="{{ old('base_price', $package->base_price) }}" required min="0" step="0.01"
                        class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-[#5c6b7f] focus:border-[#ff7b54] focus:ring-1 focus:ring-[#ff7b54] transition-colors"
                        placeholder="e.g., 50000">
                    @error('base_price')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-white mb-2">Description</label>
                <textarea name="description" id="description" rows="3"
                    class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-[#5c6b7f] focus:border-[#ff7b54] focus:ring-1 focus:ring-[#ff7b54] transition-colors"
                    placeholder="Package description">{{ old('description', $package->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Duration & Delivery -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="duration_type" class="block text-sm font-medium text-white mb-2">Duration Type *</label>
                    <select name="duration_type" id="duration_type" required
                        class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white focus:border-[#ff7b54] focus:ring-1 focus:ring-[#ff7b54] transition-colors">
                        <option value="weekly" {{ old('duration_type', $package->duration_type) == 'weekly' ? 'selected' : '' }}>Weekly</option>
                        <option value="monthly" {{ old('duration_type', $package->duration_type) == 'monthly' ? 'selected' : '' }}>Monthly</option>
                    </select>
                    @error('duration_type')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="deliveries_per_week" class="block text-sm font-medium text-white mb-2">Deliveries/Week *</label>
                    <input type="number" name="deliveries_per_week" id="deliveries_per_week" value="{{ old('deliveries_per_week', $package->deliveries_per_week) }}" required min="1" max="7"
                        class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-[#5c6b7f] focus:border-[#ff7b54] focus:ring-1 focus:ring-[#ff7b54] transition-colors">
                    @error('deliveries_per_week')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="customization_cutoff_time" class="block text-sm font-medium text-white mb-2">Customization Cutoff *</label>
                    <input type="time" name="customization_cutoff_time" id="customization_cutoff_time" value="{{ old('customization_cutoff_time', $package->customization_cutoff_time ? \Carbon\Carbon::parse($package->customization_cutoff_time)->format('H:i') : '18:00') }}" required
                        class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white focus:border-[#ff7b54] focus:ring-1 focus:ring-[#ff7b54] transition-colors">
                    @error('customization_cutoff_time')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Delivery Days -->
            <div>
                <label class="block text-sm font-medium text-white mb-3">Delivery Days *</label>
                <div class="grid grid-cols-7 gap-2">
                    @php
                        $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                        $oldDays = old('delivery_days', $package->delivery_days ?? []);
                    @endphp
                    @foreach($days as $index => $day)
                        <label class="flex flex-col items-center p-3 bg-white/5 border border-white/10 rounded-xl cursor-pointer hover:bg-white/10 transition-colors {{ in_array($index, $oldDays) ? 'border-[#ff7b54] bg-[#ff7b54]/10' : '' }}">
                            <input type="checkbox" name="delivery_days[]" value="{{ $index }}" {{ in_array($index, $oldDays) ? 'checked' : '' }}
                                class="sr-only day-checkbox">
                            <span class="text-xs font-medium text-white">{{ substr($day, 0, 3) }}</span>
                        </label>
                    @endforeach
                </div>
                @error('delivery_days')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Package Items -->
            <div>
                <div class="flex items-center justify-between mb-4">
                    <label class="block text-sm font-medium text-white">Package Items *</label>
                    <button type="button" id="addItemBtn" class="px-3 py-1.5 text-xs font-medium text-[#ff7b54] hover:text-[#ff7b54] bg-[#ff7b54]/10 hover:bg-[#ff7b54]/20 rounded-lg transition-colors">
                        + Add Item
                    </button>
                </div>
                <div id="itemsContainer" class="space-y-3">
                    @php
                        $oldItems = old('items', $package->items->map(fn($item) => ['product_id' => $item->product_id, 'default_quantity' => $item->default_quantity])->toArray());
                    @endphp
                    @if(count($oldItems) > 0)
                        @foreach($oldItems as $index => $item)
                            <div class="item-row flex items-center gap-3 p-4 bg-white/5 border border-white/10 rounded-xl">
                                <div class="flex-1 grid grid-cols-2 gap-3">
                                    <select name="items[{{ $index }}][product_id]" required
                                        class="px-4 py-2 bg-white/5 border border-white/10 rounded-lg text-white focus:border-[#ff7b54] focus:ring-1 focus:ring-[#ff7b54] transition-colors">
                                        <option value="">Select Product</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" {{ ($item['product_id'] ?? null) == $product->id ? 'selected' : '' }}>
                                                {{ $product->name }} ({{ $product->unit }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="number" name="items[{{ $index }}][default_quantity]" value="{{ $item['default_quantity'] ?? '' }}" required min="0.1" step="0.1"
                                        class="px-4 py-2 bg-white/5 border border-white/10 rounded-lg text-white placeholder-[#5c6b7f] focus:border-[#ff7b54] focus:ring-1 focus:ring-[#ff7b54] transition-colors"
                                        placeholder="Quantity">
                                </div>
                                <button type="button" class="remove-item-btn p-2 text-red-400 hover:bg-red-500/20 rounded-lg transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        @endforeach
                    @endif
                </div>
                @error('items')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Image -->
            <div>
                <label for="image" class="block text-sm font-medium text-white mb-2">Package Image</label>
                @if($package->image)
                    <div class="mb-3">
                        <img src="{{ asset('storage/' . $package->image) }}" alt="{{ $package->name }}" class="w-32 h-32 object-cover rounded-xl border border-white/10">
                    </div>
                @endif
                <input type="file" name="image" id="image" accept="image/*"
                    class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-[#ff7b54] file:text-white file:font-medium hover:file:bg-[#ff7b54]/80 file:cursor-pointer">
                @error('image')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Active Status -->
            <div>
                <label class="flex items-center space-x-3 p-4 bg-white/5 rounded-xl cursor-pointer hover:bg-white/10 transition-colors">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $package->is_active) ? 'checked' : '' }}
                        class="w-5 h-5 text-[#ff7b54] bg-white/5 border-white/10 rounded focus:ring-[#ff7b54]">
                    <span class="text-sm text-white">Package is active</span>
                </label>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center space-x-4">
            <button type="submit" class="btn-food px-6 py-3 text-white font-medium rounded-xl transition-all">
                Update Package
            </button>
            <a href="{{ route('admin.food.packages.index') }}" class="px-6 py-3 bg-white/5 hover:bg-white/10 text-white font-medium rounded-xl transition-colors">
                Cancel
            </a>
        </div>
    </form>
</div>

<script>
    let itemIndex = {{ count($oldItems ?? []) }};
    const products = @json($products);

    // Add item
    document.getElementById('addItemBtn')?.addEventListener('click', () => {
        const container = document.getElementById('itemsContainer');
        const row = document.createElement('div');
        row.className = 'item-row flex items-center gap-3 p-4 bg-white/5 border border-white/10 rounded-xl';
        row.innerHTML = `
            <div class="flex-1 grid grid-cols-2 gap-3">
                <select name="items[${itemIndex}][product_id]" required
                    class="px-4 py-2 bg-white/5 border border-white/10 rounded-lg text-white focus:border-[#ff7b54] focus:ring-1 focus:ring-[#ff7b54] transition-colors">
                    <option value="">Select Product</option>
                    ${products.map(p => `<option value="${p.id}">${p.name} (${p.unit})</option>`).join('')}
                </select>
                <input type="number" name="items[${itemIndex}][default_quantity]" required min="0.1" step="0.1"
                    class="px-4 py-2 bg-white/5 border border-white/10 rounded-lg text-white placeholder-[#5c6b7f] focus:border-[#ff7b54] focus:ring-1 focus:ring-[#ff7b54] transition-colors"
                    placeholder="Quantity">
            </div>
            <button type="button" class="remove-item-btn p-2 text-red-400 hover:bg-red-500/20 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            </button>
        `;
        container.appendChild(row);
        itemIndex++;

        // Add remove event
        row.querySelector('.remove-item-btn').addEventListener('click', () => {
            row.remove();
        });
    });

    // Remove item
    document.querySelectorAll('.remove-item-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.target.closest('.item-row').remove();
        });
    });

    // Day checkbox styling
    document.querySelectorAll('.day-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const label = this.closest('label');
            if (this.checked) {
                label.classList.add('border-[#ff7b54]', 'bg-[#ff7b54]/10');
            } else {
                label.classList.remove('border-[#ff7b54]', 'bg-[#ff7b54]/10');
            }
        });
    });
</script>
@endsection
