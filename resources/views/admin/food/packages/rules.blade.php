@extends('admin.layout')

@section('title', 'Package Rules')
@section('subtitle', 'Manage substitution rules for ' . $package->name)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('admin.food.packages.index') }}" class="inline-flex items-center text-sm text-[#a0a0a0] hover:text-white mb-2 transition-colors">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Packages
            </a>
            <p class="text-sm text-[#a0a0a0]">{{ $package->rules->count() }} rules configured</p>
        </div>
    </div>

    <!-- Package Info -->
    <div class="card p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-xl font-bold text-white">{{ $package->name }}</h2>
                <p class="text-sm text-[#6b6b6b] mt-1">{{ $package->description }}</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-[#6b6b6b]">Package Items</p>
                <p class="text-lg font-bold text-[#ff6b35]">{{ $package->items->count() }} products</p>
            </div>
        </div>
        <div class="pt-4 border-t border-[#333]">
            <p class="text-xs text-[#6b6b6b] mb-2">Package allows customers to substitute items during customization. Configure rules below to control which substitutions are allowed and any price adjustments.</p>
        </div>
    </div>

    <!-- Existing Rules -->
    <div class="card overflow-hidden">
        <div class="p-6 border-b border-[#333]">
            <h3 class="text-lg font-bold text-white">Existing Rules</h3>
            <p class="text-sm text-[#6b6b6b] mt-1">Rules define which products can be substituted and any price adjustments</p>
        </div>

        @if($package->rules->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-[#2d2d2d]">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-[#6b6b6b] uppercase">From Product</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-[#6b6b6b] uppercase">To Product</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-[#6b6b6b] uppercase">Adjustment</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-[#6b6b6b] uppercase">Status</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-[#6b6b6b] uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#333]">
                        @foreach($package->rules as $rule)
                            <tr class="hover:bg-[#2d2d2d] transition-colors">
                                <td class="px-4 py-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-[#333] rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-[#6b6b6b]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-white">{{ $rule->fromProduct->name }}</p>
                                            <p class="text-xs text-[#6b6b6b]">{{ $rule->fromProduct->unit }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center space-x-3">
                                        <svg class="w-4 h-4 text-[#6b6b6b]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium text-white">{{ $rule->toProduct->name }}</p>
                                            <p class="text-xs text-[#6b6b6b]">{{ $rule->toProduct->unit }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <p class="text-sm font-medium text-[#ff6b35]">{{ $rule->getAdjustmentLabel() }}</p>
                                    <p class="text-xs text-[#6b6b6b]">{{ ucfirst($rule->adjustment_type) }}</p>
                                </td>
                                <td class="px-4 py-4">
                                    @if($rule->is_allowed)
                                        <span class="text-xs px-2 py-1 bg-green-500/20 text-green-500 rounded-full">Allowed</span>
                                    @else
                                        <span class="text-xs px-2 py-1 bg-red-500/20 text-red-500 rounded-full">Not Allowed</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center justify-end">
                                        <form action="{{ route('admin.food.packages.rules.destroy', $rule) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this rule?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 hover:bg-red-500/20 rounded-lg transition-colors" title="Delete Rule">
                                                <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-12 text-center">
                <svg class="w-12 h-12 mx-auto text-[#333] mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="text-[#6b6b6b] mb-2">No rules configured yet</p>
                <p class="text-xs text-[#6b6b6b]">Add a rule below to allow product substitutions</p>
            </div>
        @endif
    </div>

    <!-- Add New Rule Form -->
    <div class="card p-6">
        <div class="mb-6">
            <h3 class="text-lg font-bold text-white mb-2">Add New Rule</h3>
            <p class="text-sm text-[#6b6b6b]">Create a rule to allow customers to substitute one product for another with optional price adjustment</p>
        </div>

        <form action="{{ route('admin.food.packages.rules.store', $package) }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- From Product -->
                <div>
                    <label for="from_product_id" class="block text-sm font-medium text-white mb-2">
                        From Product (Package Item) *
                    </label>
                    <select name="from_product_id" id="from_product_id" required
                        class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white focus:border-[#ff7b54] focus:ring-1 focus:ring-[#ff7b54] transition-colors">
                        <option value="">Select a package item</option>
                        @foreach($package->items as $item)
                            <option value="{{ $item->product_id }}" {{ old('from_product_id') == $item->product_id ? 'selected' : '' }}>
                                {{ $item->product->name }} ({{ $item->product->unit }}) - Default: {{ $item->default_quantity }}
                            </option>
                        @endforeach
                    </select>
                    @error('from_product_id')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-[#6b6b6b]">Select a product that is included in this package</p>
                </div>

                <!-- To Product -->
                <div>
                    <label for="to_product_id" class="block text-sm font-medium text-white mb-2">
                        To Product (Substitution) *
                    </label>
                    <select name="to_product_id" id="to_product_id" required
                        class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white focus:border-[#ff7b54] focus:ring-1 focus:ring-[#ff7b54] transition-colors">
                        <option value="">Select substitution product</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ old('to_product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->name }} ({{ $product->unit }}) - TZS {{ number_format($product->price) }}
                            </option>
                        @endforeach
                    </select>
                    @error('to_product_id')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-[#6b6b6b]">Select the product that can be substituted</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Adjustment Type -->
                <div>
                    <label for="adjustment_type" class="block text-sm font-medium text-white mb-2">
                        Adjustment Type *
                    </label>
                    <select name="adjustment_type" id="adjustment_type" required
                        class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white focus:border-[#ff7b54] focus:ring-1 focus:ring-[#ff7b54] transition-colors">
                        <option value="fixed" {{ old('adjustment_type') == 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                        <option value="percentage" {{ old('adjustment_type') == 'percentage' ? 'selected' : '' }}>Percentage</option>
                    </select>
                    @error('adjustment_type')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-[#6b6b6b]">How to calculate the price adjustment</p>
                </div>

                <!-- Adjustment Value -->
                <div>
                    <label for="adjustment_value" class="block text-sm font-medium text-white mb-2">
                        Adjustment Value *
                    </label>
                    <input type="number" name="adjustment_value" id="adjustment_value" value="{{ old('adjustment_value', 0) }}" required step="0.01"
                        class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-[#5c6b7f] focus:border-[#ff7b54] focus:ring-1 focus:ring-[#ff7b54] transition-colors"
                        placeholder="0.00">
                    @error('adjustment_value')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-[#6b6b6b]">
                        <span id="adjustment-hint">Enter amount in TZS (can be negative for discount)</span>
                    </p>
                </div>
            </div>

            <!-- Is Allowed -->
            <div>
                <label class="flex items-center space-x-3 p-4 bg-white/5 rounded-xl cursor-pointer hover:bg-white/10 transition-colors">
                    <input type="checkbox" name="is_allowed" value="1" {{ old('is_allowed', true) ? 'checked' : '' }}
                        class="w-5 h-5 text-[#ff7b54] bg-white/5 border-white/10 rounded focus:ring-[#ff7b54]">
                    <div>
                        <span class="text-sm font-medium text-white">Allow this substitution</span>
                        <p class="text-xs text-[#6b6b6b] mt-1">If unchecked, this substitution will be blocked even if the rule exists</p>
                    </div>
                </label>
            </div>

            <!-- Actions -->
            <div class="flex items-center space-x-4 pt-4 border-t border-[#333]">
                <button type="submit" class="btn-food px-6 py-3 text-white font-medium rounded-xl transition-all">
                    Add Rule
                </button>
                <a href="{{ route('admin.food.packages.index') }}" class="px-6 py-3 bg-white/5 hover:bg-white/10 text-white font-medium rounded-xl transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    // Update adjustment hint based on type
    document.getElementById('adjustment_type')?.addEventListener('change', function() {
        const hint = document.getElementById('adjustment-hint');
        const valueInput = document.getElementById('adjustment_value');
        
        if (this.value === 'percentage') {
            hint.textContent = 'Enter percentage (e.g., 10 for 10% increase, -5 for 5% discount)';
            valueInput.placeholder = '0.00';
        } else {
            hint.textContent = 'Enter amount in TZS (can be negative for discount)';
            valueInput.placeholder = '0.00';
        }
    });

    // Prevent selecting same product for from and to
    document.getElementById('from_product_id')?.addEventListener('change', function() {
        const toSelect = document.getElementById('to_product_id');
        const fromValue = this.value;
        
        if (fromValue) {
            Array.from(toSelect.options).forEach(option => {
                if (option.value === fromValue) {
                    option.disabled = true;
                    option.style.display = 'none';
                } else {
                    option.disabled = false;
                    option.style.display = '';
                }
            });
        }
    });

    document.getElementById('to_product_id')?.addEventListener('change', function() {
        const fromSelect = document.getElementById('from_product_id');
        const toValue = this.value;
        
        if (toValue) {
            Array.from(fromSelect.options).forEach(option => {
                if (option.value === toValue) {
                    option.disabled = true;
                    option.style.display = 'none';
                } else {
                    option.disabled = false;
                    option.style.display = '';
                }
            });
        }
    });
</script>
@endsection
