@extends('admin.layout')

@section('title', 'Order ' . $order->order_number)
@section('subtitle', 'View order details')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.food.orders.index') }}" class="flex items-center text-sm text-[#a0a0a0] hover:text-white transition-colors">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Orders
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Order Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Status Card -->
            <div class="card p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-white">Order Status</h3>
                    <span class="text-xs px-3 py-1 rounded-full
                        @if($order->status === 'pending') bg-yellow-500/20 text-yellow-500
                        @elseif($order->status === 'approved') bg-blue-500/20 text-blue-500
                        @elseif($order->status === 'preparing') bg-indigo-500/20 text-indigo-500
                        @elseif($order->status === 'ready') bg-cyan-500/20 text-cyan-500
                        @elseif($order->status === 'on_delivery') bg-purple-500/20 text-purple-500
                        @elseif($order->status === 'delivered') bg-green-500/20 text-green-500
                        @else bg-red-500/20 text-red-500
                        @endif
                    ">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span>
                </div>

                <form action="{{ route('admin.food.orders.update-status', $order) }}" method="POST" class="flex items-center space-x-4">
                    @csrf
                    @method('PATCH')
                    <select name="status" class="flex-1 px-4 py-2 bg-[#2d2d2d] border border-[#333] rounded-lg text-white text-sm focus:border-[#ff6b35]">
                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ $order->status == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="preparing" {{ $order->status == 'preparing' ? 'selected' : '' }}>Preparing</option>
                        <option value="ready" {{ $order->status == 'ready' ? 'selected' : '' }}>Ready</option>
                        <option value="on_delivery" {{ $order->status == 'on_delivery' ? 'selected' : '' }}>On Delivery</option>
                        <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        <option value="rejected" {{ $order->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                    <button type="submit" class="px-4 py-2 bg-[#ff6b35] text-white font-medium rounded-lg hover:bg-[#e55a2b] transition-colors">
                        Update
                    </button>
                </form>
            </div>

            <!-- Order Items -->
            <div class="card overflow-hidden">
                <div class="p-4 border-b border-[#333]">
                    <h3 class="font-bold text-white">Order Items</h3>
                </div>
                <div class="divide-y divide-[#333]">
                    @foreach($order->items as $item)
                        <div class="p-4 flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-[#333] rounded-lg flex items-center justify-center overflow-hidden">
                                    @if($item->product && $item->product->image)
                                        <img src="{{ asset('storage/' . $item->product->image) }}" alt="" class="w-full h-full object-cover">
                                    @else
                                        <svg class="w-6 h-6 text-[#6b6b6b]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-white">{{ $item->product_name }}</p>
                                    <p class="text-xs text-[#6b6b6b]">TZS {{ number_format($item->unit_price) }} x {{ $item->quantity }} {{ $item->unit }}</p>
                                </div>
                            </div>
                            <p class="text-sm font-medium text-white">TZS {{ number_format($item->total_price) }}</p>
                        </div>
                    @endforeach
                </div>
                <div class="p-4 border-t border-[#333] bg-[#2d2d2d]">
                    <div class="flex items-center justify-between">
                        <span class="text-lg font-bold text-white">Total</span>
                        <span class="text-lg font-bold text-[#ff6b35]">TZS {{ number_format($order->total_amount) }}</span>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            @if($order->notes)
                <div class="card p-6">
                    <h3 class="font-bold text-white mb-3">Order Notes</h3>
                    <p class="text-sm text-[#a0a0a0]">{{ $order->notes }}</p>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Customer Info -->
            <div class="card p-6">
                <h3 class="font-bold text-white mb-4">Customer</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-[#6b6b6b]">Name</p>
                        <p class="text-white">{{ $order->user->name ?? 'Guest' }}</p>
                    </div>
                    <div>
                        <p class="text-[#6b6b6b]">Phone</p>
                        <p class="text-white">{{ $order->user->phone ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-[#6b6b6b]">Email</p>
                        <p class="text-white">{{ $order->user->email ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Delivery Info -->
            <div class="card p-6">
                <h3 class="font-bold text-white mb-4">Delivery</h3>
                <div class="space-y-3 text-sm mb-4">
                    <div>
                        <p class="text-[#6b6b6b]">Address</p>
                        <p class="text-white">{{ $order->delivery_address ?? '-' }}</p>
                    </div>
                    @if($order->assignedUser)
                        <div>
                            <p class="text-[#6b6b6b]">Assigned To</p>
                            <p class="text-white">{{ $order->assignedUser->name }}</p>
                        </div>
                    @endif
                </div>

                @if($order->delivery_lat && $order->delivery_lng)
                    <!-- Map Section -->
                    <div class="mt-4 pt-4 border-t border-[#333]">
                        <h4 class="font-semibold text-white mb-3">Delivery Location</h4>
                        <div id="order-map" class="w-full h-64 sm:h-80 rounded-lg overflow-hidden border border-[#333]"></div>
                        <div id="map-info" class="mt-3 space-y-2">
                            <button id="get-directions-btn" class="w-full px-4 py-2 bg-[#ff6b35] hover:bg-[#e55a2b] text-white font-medium rounded-lg transition-colors text-sm">
                                Get Directions
                            </button>
                            <div id="route-info" class="hidden text-xs text-[#a0a0a0] space-y-1"></div>
                        </div>
                    </div>
                @endif

                @if(!$order->assignedUser && $deliveryStaff->isNotEmpty())
                    <form action="{{ route('admin.food.orders.assign', $order) }}" method="POST" class="mt-4 pt-4 border-t border-[#333]">
                        @csrf
                        @method('PATCH')
                        <select name="assigned_to" class="w-full px-4 py-2 bg-[#2d2d2d] border border-[#333] rounded-lg text-white text-sm focus:border-[#ff6b35] mb-2">
                            <option value="">Assign to...</option>
                            @foreach($deliveryStaff as $staff)
                                <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="w-full px-4 py-2 bg-[#ff6b35] text-white font-medium rounded-lg hover:bg-[#e55a2b] transition-colors">
                            Assign Delivery
                        </button>
                    </form>
                @endif
            </div>

            <!-- Order Details -->
            <div class="card p-6">
                <h3 class="font-bold text-white mb-4">Details</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-[#6b6b6b]">Order Number</p>
                        <p class="text-white font-mono">{{ $order->order_number }}</p>
                    </div>
                    <div>
                        <p class="text-[#6b6b6b]">Source</p>
                        <p class="text-white">{{ ucfirst($order->source) }}</p>
                    </div>
                    <div>
                        <p class="text-[#6b6b6b]">Created</p>
                        <p class="text-white">{{ $order->created_at->format('M d, Y H:i') }}</p>
                    </div>
                    @if($order->delivered_at)
                        <div>
                            <p class="text-[#6b6b6b]">Delivered</p>
                            <p class="text-white">{{ $order->delivered_at->format('M d, Y H:i') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if($order->delivery_lat && $order->delivery_lng)
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const orderLat = {{ $order->delivery_lat }};
    const orderLng = {{ $order->delivery_lng }};
    const orderNumber = '{{ $order->order_number }}';
    const deliveryAddress = '{{ addslashes($order->delivery_address) }}';
    const markerColor = '#ff6b35'; // Orange for Food orders
    
    // Initialize map
    const map = L.map('order-map').setView([orderLat, orderLng], 15);
    
    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);
    
    // Create custom icon
    const markerIcon = L.divIcon({
        className: 'custom-marker',
        html: `<div style="width: 32px; height: 32px; background-color: ${markerColor}; border: 3px solid white; border-radius: 50%; box-shadow: 0 2px 8px rgba(0,0,0,0.3);"></div>`,
        iconSize: [32, 32],
        iconAnchor: [16, 32],
        popupAnchor: [0, -32]
    });
    
    // Add marker
    const marker = L.marker([orderLat, orderLng], { icon: markerIcon })
        .addTo(map)
        .bindPopup(`
            <div style="min-width: 200px;">
                <strong style="color: #1a1a1a;">${orderNumber}</strong><br>
                <span style="color: #6b6b6b; font-size: 12px;">${deliveryAddress}</span>
            </div>
        `);
    
    marker.openPopup();
    
    let routingControl = null;
    let currentUserPosition = null;
    
    // Get Directions button handler
    document.getElementById('get-directions-btn').addEventListener('click', function() {
        const btn = this;
        const routeInfo = document.getElementById('route-info');
        
        if (routingControl) {
            map.removeControl(routingControl);
            routingControl = null;
            btn.textContent = 'Get Directions';
            routeInfo.classList.add('hidden');
            return;
        }
        
        if (navigator.geolocation) {
            btn.textContent = 'Getting your location...';
            btn.disabled = true;
            
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    currentUserPosition = [position.coords.latitude, position.coords.longitude];
                    
                    routingControl = L.Routing.control({
                        waypoints: [
                            L.latLng(currentUserPosition[0], currentUserPosition[1]),
                            L.latLng(orderLat, orderLng)
                        ],
                        router: L.Routing.osrmv1({
                            serviceUrl: 'https://router.project-osrm.org/route/v1'
                        }),
                        lineOptions: {
                            styles: [{ color: markerColor, opacity: 0.8, weight: 5 }]
                        },
                        createMarker: function() { return null; },
                        show: true,
                        addWaypoints: false,
                        routeWhileDragging: false
                    }).addTo(map);
                    
                    routingControl.on('routesfound', function(e) {
                        const routes = e.routes;
                        if (routes && routes[0]) {
                            const distance = (routes[0].summary.totalDistance / 1000).toFixed(2);
                            const duration = Math.round(routes[0].summary.totalTime / 60);
                            
                            routeInfo.innerHTML = `
                                <div class="flex items-center justify-between">
                                    <span>Distance: <strong class="text-white">${distance} km</strong></span>
                                    <span>Est. Time: <strong class="text-white">${duration} min</strong></span>
                                </div>
                            `;
                            routeInfo.classList.remove('hidden');
                        }
                    });
                    
                    map.fitBounds([
                        [currentUserPosition[0], currentUserPosition[1]],
                        [orderLat, orderLng]
                    ], { padding: [50, 50] });
                    
                    btn.textContent = 'Clear Route';
                    btn.disabled = false;
                },
                function(error) {
                    alert('Unable to get your location. Please enable location access or manually navigate to: ' + deliveryAddress);
                    btn.textContent = 'Get Directions';
                    btn.disabled = false;
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000
                }
            );
        } else {
            alert('Geolocation is not supported by your browser.');
        }
    });
});
</script>

<style>
.custom-marker {
    background: transparent !important;
    border: none !important;
}
.leaflet-routing-container {
    background: #2d2d2d !important;
    color: white !important;
    border: 1px solid #333 !important;
}
.leaflet-routing-container h2 {
    color: white !important;
}
.leaflet-routing-container .leaflet-routing-alt {
    background: #1a1a1a !important;
    color: #a0a0a0 !important;
}
</style>
@endif
@endsection
