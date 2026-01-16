@extends('layouts.app')

@section('title', 'Custom Order - Monana Food')

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
                    Back to Monana Food
                </a>
                <h1 class="text-2xl sm:text-3xl font-bold text-white">Custom Order</h1>
                <p class="text-sm sm:text-base text-[#a0a0a0] mt-1">Chagua bidhaa unavyohitaji na idadi yake</p>
            </div>
        </div>
    </div>

    @if($products->isEmpty())
        <div class="card p-6 sm:p-8 lg:p-12 text-center">
            <svg class="w-12 h-12 sm:w-16 sm:h-16 text-[#333] mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
            </svg>
            <h3 class="text-lg sm:text-xl font-bold text-white mb-2">No products available</h3>
            <p class="text-sm sm:text-base text-[#a0a0a0]">No products are currently available for custom orders.</p>
        </div>
    @else
        <!-- Products Grid -->
        <div id="products-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 pb-20 sm:pb-24">
            @foreach($products as $product)
                <div class="card overflow-hidden group" data-item-id="{{ $product->id }}">
                    <div class="aspect-video bg-[#333] relative overflow-hidden">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <svg class="w-10 h-10 sm:w-12 sm:h-12 text-[#6b6b6b]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                        @endif
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
                                <button type="button" onclick="getCurrentLocation()" id="gps-btn"
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
    @endif
</div>

<script>
const cart = {};
const prices = {};
const productUnits = {};
const productNames = {};

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
        getCurrentLocation(true); // Pass true to indicate auto-capture (don't show button loading)
        document.getElementById('delivery-address').focus();
    }, 100);
}

function closeCheckoutModal() {
    const modal = document.getElementById('checkout-modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function getCurrentLocation(isAutoCapture = false) {
    const gpsBtn = document.getElementById('gps-btn');
    const gpsStatus = document.getElementById('gps-status');
    const addressInput = document.getElementById('delivery-address');
    
    if (!navigator.geolocation) {
        if (!isAutoCapture) {
            gpsStatus.textContent = 'Geolocation is not supported by your browser.';
            gpsStatus.className = 'text-xs text-red-500 mt-1';
        }
        return;
    }

    // Only show button loading state if manually triggered
    if (!isAutoCapture) {
        gpsBtn.disabled = true;
        gpsBtn.innerHTML = '<svg class="w-5 h-5 inline-block animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg> Getting location...';
    }
    gpsStatus.textContent = 'Getting your location...';
    gpsStatus.className = 'text-xs text-[#ff6b35] mt-1';

    navigator.geolocation.getCurrentPosition(
        function(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;

            // Store coordinates
            document.getElementById('delivery-lat').value = lat;
            document.getElementById('delivery-lng').value = lng;

            // Show coordinates and prompt for address
            gpsStatus.textContent = `Location captured (${lat.toFixed(6)}, ${lng.toFixed(6)}). Please enter your full address below.`;
            gpsStatus.className = 'text-xs text-green-500 mt-1';
            
            // Clear address input so user can type
            if (!addressInput.value) {
                addressInput.placeholder = 'Enter your delivery address (coordinates captured)';
            }
            
            // Only update button if manually triggered
            if (!isAutoCapture) {
                gpsBtn.disabled = false;
                gpsBtn.innerHTML = `
                    <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Retry GPS
                `;
            } else {
                // Reset button for auto-capture
                if (gpsBtn) {
                    gpsBtn.disabled = false;
                    gpsBtn.innerHTML = `
                        <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Retry GPS
                    `;
                }
            }
        },
        function(error) {
            // Only show error if not auto-capture, or show minimal message
            if (!isAutoCapture) {
                gpsBtn.disabled = false;
                gpsBtn.innerHTML = `
                    <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Retry GPS
                `;
            } else {
                // Reset button for auto-capture failure
                if (gpsBtn) {
                    gpsBtn.disabled = false;
                    gpsBtn.innerHTML = `
                        <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Get GPS
                    `;
                }
            }
            
            // Show minimal error for auto-capture, full error for manual
            if (!isAutoCapture) {
                let errorMsg = 'Unable to get your location. ';
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        errorMsg += 'Location access denied.';
                        break;
                    case error.POSITION_UNAVAILABLE:
                        errorMsg += 'Location information unavailable.';
                        break;
                    case error.TIMEOUT:
                        errorMsg += 'Location request timeout.';
                        break;
                    default:
                        errorMsg += 'An unknown error occurred.';
                        break;
                }
                gpsStatus.textContent = errorMsg;
                gpsStatus.className = 'text-xs text-red-500 mt-1';
            } else {
                // Silent fail for auto-capture, just show that user can click button
                gpsStatus.textContent = 'Click "Get GPS" button if you want to share your location.';
                gpsStatus.className = 'text-xs text-[#6b6b6b] mt-1';
            }
        },
        {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 0
        }
    );
}

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
