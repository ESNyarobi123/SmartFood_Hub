@extends('layouts.app')

@section('title', 'Custom Order - Monana Market')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-12">
    <!-- Header -->
    <div class="mb-6 sm:mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 md:gap-0">
            <div>
                <a href="{{ route('food.index') }}" class="text-sm text-[#a0a0a0] hover:text-white mb-2 inline-flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Back to Monana Market
                </a>
                <h1 class="text-2xl sm:text-3xl font-bold text-white">Custom Order</h1>
                <p class="text-sm sm:text-base text-[#a0a0a0] mt-1">Chagua bidhaa unavyohitaji na idadi yake</p>
            </div>
        </div>
    </div>

    <!-- Search / Filter Bar -->
    @if($products->isNotEmpty())
    <div class="mb-5 sm:mb-6" x-data="{ search: '' }" x-init="$watch('search', val => filterProducts(val))">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-[#6b6b6b]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" x-model="search" placeholder="Tafuta bidhaa... mfano: mchele, sukari, nyanya"
                class="w-full pl-12 pr-10 py-3 bg-[#1a1a1a] border border-[#333] rounded-xl text-white text-sm placeholder-[#555] focus:outline-none focus:border-[#ff6b35]/50 focus:ring-1 focus:ring-[#ff6b35]/20 transition-all">
            <button x-show="search.length > 0" @click="search = ''" class="absolute inset-y-0 right-0 pr-4 flex items-center text-[#6b6b6b] hover:text-white transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <p id="search-count" class="text-xs text-[#6b6b6b] mt-2 hidden"></p>
    </div>
    @endif

    @if($products->isEmpty())
        <div class="card p-6 sm:p-8 lg:p-12 text-center">
            <svg class="w-12 h-12 sm:w-16 sm:h-16 text-[#333] mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
            </svg>
            <h3 class="text-lg sm:text-xl font-bold text-white mb-2">No products available</h3>
            <p class="text-sm sm:text-base text-[#a0a0a0]">No products are currently available for custom orders.</p>
        </div>
    @else
        <div x-data="{ loaded: false }" x-init="$nextTick(() => { setTimeout(() => loaded = true, 150) })">
        <!-- Skeleton Grid -->
        <div x-show="!loaded" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 pb-20 sm:pb-24">
            @for($i = 0; $i < min(6, $products->count()); $i++)
                <div class="card overflow-hidden">
                    <div class="aspect-video skeleton"></div>
                    <div class="p-3 sm:p-4 space-y-3">
                        <div class="skeleton h-5 w-3/4 rounded"></div>
                        <div class="skeleton h-3 w-full rounded"></div>
                        <div class="skeleton h-3 w-1/3 rounded"></div>
                        <div class="flex items-center justify-between pt-2">
                            <div class="flex items-center space-x-3">
                                <div class="skeleton w-8 h-8 rounded-lg"></div>
                                <div class="skeleton w-8 h-5 rounded"></div>
                                <div class="skeleton w-8 h-8 rounded-lg"></div>
                            </div>
                            <div class="skeleton h-4 w-16 rounded"></div>
                        </div>
                    </div>
                </div>
            @endfor
        </div>

        <!-- Products Grid -->
        @php
            $customFallbacks = [
                'https://images.unsplash.com/photo-1586201375761-83865001e31c?w=400&h=300&fit=crop&q=75',
                'https://images.unsplash.com/photo-1558618666-fcd25c85f82e?w=400&h=300&fit=crop&q=75',
                'https://images.unsplash.com/photo-1587486913049-53fc88980cfc?w=400&h=300&fit=crop&q=75',
                'https://images.unsplash.com/photo-1598170845058-32b9d6a5da37?w=400&h=300&fit=crop&q=75',
                'https://images.unsplash.com/photo-1509440159596-0249088772ff?w=400&h=300&fit=crop&q=75',
                'https://images.unsplash.com/photo-1574323347407-f5e1ad6d020b?w=400&h=300&fit=crop&q=75',
            ];
        @endphp
        <div x-show="loaded" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" id="products-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 pb-20 sm:pb-24">
            @foreach($products as $product)
                <div class="card overflow-hidden group hover:border-[#ff6b35]/30 transition-all duration-300" data-item-id="{{ $product->id }}" data-name="{{ strtolower($product->name) }}">
                    <div class="aspect-video bg-[#2a2a2a] relative overflow-hidden">
                        <img src="{{ $product->image ? asset('storage/' . $product->image) : $customFallbacks[$loop->index % count($customFallbacks)] }}"
                             alt="{{ $product->name }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                             loading="lazy"
                             onerror="this.onerror=null;this.src='{{ $customFallbacks[$loop->index % count($customFallbacks)] }}'">
                        <div class="absolute top-2 right-2 px-2 py-1 bg-[#ff6b35] text-white text-xs sm:text-sm font-bold rounded">
                            TZS {{ number_format($product->price, 0) }}/{{ $product->unit }}
                        </div>
                        @if($product->stock_quantity > 0)
                            <div class="absolute top-2 left-2 px-2 py-1 bg-green-500/80 text-white text-xs font-medium rounded">
                                Stock: {{ number_format($product->stock_quantity, $product->unit === 'kg' ? 1 : 0) }}
                            </div>
                        @endif
                    </div>
                    <div class="p-3 sm:p-4">
                        <h3 class="text-base sm:text-lg font-bold text-white mb-1">{{ $product->name }}</h3>
                        <p class="text-xs sm:text-sm text-[#6b6b6b] mb-2">{{ Str::limit($product->description, 60) }}</p>
                        <p class="text-xs text-[#a0a0a0] mb-3 sm:mb-4">Unit: {{ $product->unit }}</p>

                        <!-- Quantity Controls -->
                        <div class="flex items-center justify-between gap-2">
                            <div class="flex items-center space-x-2 sm:space-x-3">
                                <button onclick="decreaseQty({{ $product->id }})" class="w-7 h-7 sm:w-8 sm:h-8 bg-[#333] hover:bg-[#444] rounded-lg flex items-center justify-center transition-colors">
                                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                    </svg>
                                </button>
                                <span id="qty-{{ $product->id }}" class="text-base sm:text-lg font-bold text-white w-8 sm:w-10 text-center">0</span>
                                <button onclick="increaseQty({{ $product->id }})" class="w-7 h-7 sm:w-8 sm:h-8 bg-[#ff6b35] hover:bg-[#e55a2b] rounded-lg flex items-center justify-center transition-colors">
                                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                </button>
                            </div>
                            <span id="total-{{ $product->id }}" class="text-xs sm:text-sm font-medium text-[#ff6b35] text-right flex-shrink-0" data-price="{{ $product->price }}">TZS 0</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Cart Summary -->
        <div id="cart-summary" class="fixed bottom-0 left-0 right-0 bg-[#1a1a1a] border-t border-[#333] p-3 sm:p-4 transform translate-y-full transition-transform duration-300 z-50 shadow-2xl">
            <div class="max-w-7xl mx-auto flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-0">
                <div class="flex items-center justify-between sm:justify-start w-full sm:w-auto">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-0">
                        <span id="cart-count" class="text-xs sm:text-sm text-[#a0a0a0] font-medium">0 items</span>
                        <span id="cart-total" class="text-base sm:text-lg lg:text-xl font-bold text-white sm:ml-4">TZS 0</span>
                    </div>
                </div>
                <button onclick="openCheckoutModal()" class="w-full sm:w-auto px-4 sm:px-6 py-2.5 sm:py-3 bg-[#ff6b35] hover:bg-[#e55a2b] text-white text-sm sm:text-base font-bold rounded-lg transition-colors shadow-lg">
                    Proceed to Checkout
                </button>
            </div>
        </div>

        <!-- Checkout Modal -->
        <div id="checkout-modal" class="fixed inset-0 bg-black/70 z-[100] hidden items-center justify-center p-4" onclick="if(event.target === this) closeCheckoutModal()">
            <div class="bg-[#1a1a1a] rounded-xl max-w-lg w-full max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
                <div class="p-4 sm:p-6">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between mb-4 pb-4 border-b border-[#333]">
                        <h2 class="text-xl font-bold text-white">Checkout</h2>
                        <button onclick="closeCheckoutModal()" class="text-[#a0a0a0] hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Order Summary -->
                    <div class="mb-6">
                        <h3 class="text-sm font-semibold text-[#a0a0a0] mb-3">Order Summary</h3>
                        <div id="modal-order-items" class="space-y-2 mb-4"></div>
                        <div class="flex items-center justify-between pt-3 border-t border-[#333]">
                            <span class="text-lg font-semibold text-white">Total</span>
                            <span id="modal-total" class="text-xl font-bold text-[#ff6b35]">TZS 0</span>
                        </div>
                    </div>

                    <!-- Delivery Address Form -->
                    <form id="checkout-form" onsubmit="submitCheckout(event)">
                        <!-- Location Input -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-white mb-2">Delivery Address <span class="text-red-500">*</span></label>
                            <div class="mb-2">
                                <textarea id="delivery-address" name="delivery_address" required rows="3"
                                    class="w-full px-4 py-2.5 bg-[#2d2d2d] border border-[#333] rounded-lg text-white text-sm focus:outline-none focus:border-[#ff6b35] transition-colors resize-y"
                                    placeholder="Andika anwani yako kamili ya utumaji...&#10;Mfano:&#10;Soko la Kariakoo&#10;Barabara ya Nyamwezi&#10;Jengo la Mkuu, Ghorofa ya 2"></textarea>
                            </div>
                            <div class="flex justify-end">
                                <button type="button" onclick="getCurrentLocation(false, '#ff6b35')" id="gps-btn"
                                    class="px-4 py-2.5 bg-[#ff6b35] hover:bg-[#e55a2b] text-white font-semibold rounded-lg transition-colors text-sm whitespace-nowrap">
                                    <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    Pata Location (GPS)
                                </button>
                            </div>
                            <p id="gps-status" class="text-xs text-[#6b6b6b] mt-1"></p>
                            <input type="hidden" id="delivery-lat" name="delivery_lat">
                            <input type="hidden" id="delivery-lng" name="delivery_lng">
                        </div>

                        <!-- Notes -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-white mb-2">Additional Notes (Optional)</label>
                            <textarea name="notes" rows="3"
                                class="w-full px-4 py-2.5 bg-[#2d2d2d] border border-[#333] rounded-lg text-white text-sm focus:outline-none focus:border-[#ff6b35] transition-colors resize-none"
                                placeholder="Any special instructions..."></textarea>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex gap-3">
                            <button type="button" onclick="closeCheckoutModal()"
                                class="flex-1 px-4 py-3 bg-[#333] hover:bg-[#444] text-white font-semibold rounded-lg transition-colors">
                                Cancel
                            </button>
                            <button type="submit" id="submit-checkout-btn"
                                class="flex-1 px-4 py-3 bg-[#ff6b35] hover:bg-[#e55a2b] text-white font-bold rounded-lg transition-colors">
                                Place Order
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        </div>
    @endif
</div>

<script>
const cart = {};
const prices = {};
const productUnits = {};
const productNames = {};

function filterProducts(query) {
    const cards = document.querySelectorAll('#products-container [data-item-id]');
    const q = query.toLowerCase().trim();
    let visible = 0;
    cards.forEach(card => {
        const name = card.getAttribute('data-name') || '';
        if (!q || name.includes(q)) {
            card.style.display = '';
            visible++;
        } else {
            card.style.display = 'none';
        }
    });
    const countEl = document.getElementById('search-count');
    if (q) {
        countEl.textContent = `Bidhaa ${visible} zimepatikana`;
        countEl.classList.remove('hidden');
    } else {
        countEl.classList.add('hidden');
    }
}

document.querySelectorAll('[data-price]').forEach(el => {
    const itemId = el.id.replace('total-', '');
    prices[itemId] = parseFloat(el.dataset.price);
    const productCard = document.querySelector(`[data-item-id="${itemId}"]`);
    if (productCard) {
        const nameEl = productCard.querySelector('h3');
        const unitEl = productCard.querySelector('p.text-xs.text-\\[\\#a0a0a0\\]');
        if (nameEl) productNames[itemId] = nameEl.textContent.trim();
        if (unitEl && unitEl.textContent.includes('Unit:')) {
            productUnits[itemId] = unitEl.textContent.replace('Unit:', '').trim();
        }
    }
});

function updateCart() {
    let totalItems = 0;
    let totalAmount = 0;

    Object.entries(cart).forEach(([id, qty]) => {
        if (qty > 0) {
            totalItems += qty;
            totalAmount += qty * prices[id];
            document.getElementById(`total-${id}`).textContent = `TZS ${(qty * prices[id]).toLocaleString()}`;
        } else {
            document.getElementById(`total-${id}`).textContent = 'TZS 0';
        }
    });

    document.getElementById('cart-count').textContent = `${totalItems.toFixed(1)} items`;
    document.getElementById('cart-total').textContent = `TZS ${totalAmount.toLocaleString()}`;

    const cartSummary = document.getElementById('cart-summary');
    if (totalItems > 0) {
        cartSummary.classList.remove('translate-y-full');
    } else {
        cartSummary.classList.add('translate-y-full');
    }
}

function increaseQty(productId) {
    cart[productId] = (cart[productId] || 0) + 1;
    document.getElementById(`qty-${productId}`).textContent = cart[productId];
    updateCart();
}

function decreaseQty(productId) {
    if (cart[productId] && cart[productId] > 0) {
        cart[productId]--;
        document.getElementById(`qty-${productId}`).textContent = cart[productId];
        updateCart();
    }
}

function openCheckoutModal() {
    const items = Object.entries(cart).filter(([id, qty]) => qty > 0);
    if (items.length === 0) {
        alert('Please add items to your cart');
        return;
    }

    // Populate order summary
    const modalItems = document.getElementById('modal-order-items');
    modalItems.innerHTML = '';
    
    let totalAmount = 0;
    items.forEach(([id, qty]) => {
        const price = prices[id] || 0;
        const total = price * qty;
        totalAmount += total;
        const unit = productUnits[id] || '';
        
        const itemDiv = document.createElement('div');
        itemDiv.className = 'flex items-center justify-between text-sm';
        itemDiv.innerHTML = `
            <div>
                <span class="text-white">${productNames[id] || 'Item'} × ${qty} ${unit}</span>
            </div>
            <span class="text-[#ff6b35] font-semibold">TZS ${total.toLocaleString()}</span>
        `;
        modalItems.appendChild(itemDiv);
    });

    document.getElementById('modal-total').textContent = `TZS ${totalAmount.toLocaleString()}`;

    // Show modal
    const modal = document.getElementById('checkout-modal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    // Automatically capture GPS location when modal opens
    setTimeout(() => {
        getCurrentLocation(true, '#ff6b35'); // Pass true to indicate auto-capture (don't show button loading)
        document.getElementById('delivery-address').focus();
    }, 100);
}

function closeCheckoutModal() {
    const modal = document.getElementById('checkout-modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// getCurrentLocation is now provided by the shared gps.js module (resources/js/gps.js)
// Called with: getCurrentLocation(isAutoCapture, accentColor)

function submitCheckout(event) {
    event.preventDefault();
    
    const items = Object.entries(cart).filter(([id, qty]) => qty > 0);
    if (items.length === 0) {
        alert('Please add items to your cart');
        return;
    }

    const submitBtn = document.getElementById('submit-checkout-btn');
    submitBtn.disabled = true;
    submitBtn.textContent = 'Processing...';

    // Build form and submit
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("food.order.store") }}';

    const csrf = document.createElement('input');
    csrf.type = 'hidden';
    csrf.name = '_token';
    csrf.value = '{{ csrf_token() }}';
    form.appendChild(csrf);

    items.forEach(([id, qty], index) => {
        const idInput = document.createElement('input');
        idInput.type = 'hidden';
        idInput.name = `items[${index}][product_id]`;
        idInput.value = id;
        form.appendChild(idInput);

        const qtyInput = document.createElement('input');
        qtyInput.type = 'hidden';
        qtyInput.name = `items[${index}][quantity]`;
        qtyInput.value = qty;
        form.appendChild(qtyInput);
    });

    // Add delivery address
    const addressInput = document.createElement('input');
    addressInput.type = 'hidden';
    addressInput.name = 'delivery_address';
    addressInput.value = document.getElementById('delivery-address').value;
    form.appendChild(addressInput);

    // Add coordinates if available
    const latInput = document.createElement('input');
    latInput.type = 'hidden';
    latInput.name = 'delivery_lat';
    latInput.value = document.getElementById('delivery-lat').value || '';
    form.appendChild(latInput);

    const lngInput = document.createElement('input');
    lngInput.type = 'hidden';
    lngInput.name = 'delivery_lng';
    lngInput.value = document.getElementById('delivery-lng').value || '';
    form.appendChild(lngInput);

    // Add notes if provided
    const notesInput = document.getElementById('checkout-form').querySelector('[name="notes"]');
    if (notesInput && notesInput.value.trim()) {
        const notesHidden = document.createElement('input');
        notesHidden.type = 'hidden';
        notesHidden.name = 'notes';
        notesHidden.value = notesInput.value.trim();
        form.appendChild(notesHidden);
    }

    document.body.appendChild(form);
    form.submit();
}
</script>
@endsection
