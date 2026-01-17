@extends('admin.layout')

@section('title', 'Orders Map')
@section('subtitle', 'View all active orders on map')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-white">Orders Map</h1>
            <p class="text-sm text-[#a0a0a0] mt-1">View all active Monana Food orders with GPS locations</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.cyber.orders.index') }}" class="px-4 py-2 bg-[#333] hover:bg-[#444] text-white text-sm font-medium rounded-lg transition-colors">
                Back to Orders
            </a>
            <button onclick="copyMapUrl()" class="px-4 py-2 bg-[#00d4aa] hover:bg-[#00b894] text-black text-sm font-bold rounded-lg transition-colors">
                Copy Map URL
            </button>
        </div>
    </div>

    <!-- Legend -->
    <div class="card p-4">
        <div class="flex flex-wrap items-center gap-4 text-sm">
            <span class="text-[#a0a0a0] font-medium">Legend:</span>
            <div class="flex items-center gap-2">
                <div class="w-4 h-4 rounded-full bg-[#ff6b35]"></div>
                <span class="text-white">Asubuhi</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-4 h-4 rounded-full bg-[#dc2626]"></div>
                <span class="text-white">Mchana</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-4 h-4 rounded-full bg-[#1a1a1a] border border-white"></div>
                <span class="text-white">Usiku</span>
            </div>
        </div>
    </div>

    <!-- Map Container -->
    <div class="card p-0 overflow-hidden">
        <div id="orders-map" class="w-full h-[600px] sm:h-[700px]"></div>
    </div>

    <!-- Orders List -->
    <div class="card p-4 sm:p-6">
        <h2 class="text-lg font-bold text-white mb-4">Orders on Map (<span id="orders-count">{{ $orders->count() }}</span>)</h2>
        @if($orders->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
                @foreach($orders as $order)
                    <div class="p-3 bg-[#2d2d2d] rounded-lg hover:bg-[#333] transition-colors cursor-pointer order-card" 
                         data-order-id="{{ $order['id'] }}"
                         data-lat="{{ $order['lat'] }}"
                         data-lng="{{ $order['lng'] }}">
                        <div class="flex items-start justify-between mb-2">
                            <div class="flex-1">
                                <a href="{{ $order['url'] }}" class="text-sm font-medium text-[#00d4aa] hover:underline">
                                    {{ $order['order_number'] }}
                                </a>
                                <p class="text-xs text-[#6b6b6b] mt-1">{{ $order['customer'] }}</p>
                            </div>
                            <div class="w-3 h-3 rounded-full flex-shrink-0" style="background-color: {{ $order['color'] }};"></div>
                        </div>
                        <p class="text-xs text-[#a0a0a0] mb-1">{{ $order['meal_slot'] }}</p>
                        <p class="text-xs text-white font-medium">TZS {{ number_format($order['total']) }}</p>
                        <p class="text-xs text-[#6b6b6b] mt-1 line-clamp-1">{{ $order['address'] }}</p>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <p class="text-[#6b6b6b]">No orders with GPS locations available</p>
            </div>
        @endif
    </div>
</div>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.min.js"></script>

<script>
const orders = @json($orders);
let map;
let markers = [];
let routingControl = null;
let selectedMarker = null;

document.addEventListener('DOMContentLoaded', function() {
    // Initialize map centered on Tanzania (Dar es Salaam)
    map = L.map('orders-map').setView([-6.7924, 39.2083], 12);
    
    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);
    
    // Create markers for each order
    orders.forEach(order => {
        const markerIcon = L.divIcon({
            className: 'custom-order-marker',
            html: `<div style="width: 24px; height: 24px; background-color: ${order.color}; border: 3px solid white; border-radius: 50%; box-shadow: 0 2px 8px rgba(0,0,0,0.4); cursor: pointer;"></div>`,
            iconSize: [24, 24],
            iconAnchor: [12, 24],
            popupAnchor: [0, -24]
        });
        
        const marker = L.marker([order.lat, order.lng], { icon: markerIcon })
            .addTo(map)
            .bindPopup(`
                <div style="min-width: 220px;">
                    <strong style="color: #1a1a1a; font-size: 14px;">${order.order_number}</strong><br>
                    <span style="color: #6b6b6b; font-size: 12px;">${order.customer}</span><br>
                    <span style="color: #6b6b6b; font-size: 12px;">${order.meal_slot}</span><br>
                    <span style="color: #1a1a1a; font-weight: bold;">TZS ${parseFloat(order.total).toLocaleString()}</span><br>
                    <p style="color: #6b6b6b; font-size: 11px; margin-top: 4px;">${order.address}</p>
                    <div style="margin-top: 8px; display: flex; gap: 4px;">
                        <a href="${order.url}" style="padding: 4px 8px; background: #00d4aa; color: black; text-decoration: none; border-radius: 4px; font-size: 11px; font-weight: bold;">View</a>
                        <button onclick="showDirections(${order.lat}, ${order.lng}, '${order.order_number}', '${order.address.replace(/'/g, "\\'")}')" style="padding: 4px 8px; background: #333; color: white; border: none; border-radius: 4px; font-size: 11px; cursor: pointer;">Directions</button>
                    </div>
                </div>
            `);
        
        marker.orderData = order;
        markers.push(marker);
        
        // Click marker to show popup
        marker.on('click', function() {
            selectedMarker = marker;
            highlightOrderCard(order.id);
        });
    });
    
    // Fit map to show all markers
    if (markers.length > 0) {
        const group = new L.featureGroup(markers);
        map.fitBounds(group.getBounds().pad(0.1));
    }
    
    // Order card click handlers
    document.querySelectorAll('.order-card').forEach(card => {
        card.addEventListener('click', function() {
            const orderId = parseInt(this.dataset.orderId);
            const marker = markers.find(m => m.orderData.id === orderId);
            if (marker) {
                map.setView([marker.getLatLng().lat, marker.getLatLng().lng], 15);
                marker.openPopup();
                highlightOrderCard(orderId);
            }
        });
    });
});

function highlightOrderCard(orderId) {
    document.querySelectorAll('.order-card').forEach(card => {
        card.classList.remove('ring-2', 'ring-[#00d4aa]');
        if (parseInt(card.dataset.orderId) === orderId) {
            card.classList.add('ring-2', 'ring-[#00d4aa]');
        }
    });
}

function showDirections(destLat, destLng, orderNumber, address) {
    if (routingControl) {
        map.removeControl(routingControl);
        routingControl = null;
    }
    
    // Try to get user's current location
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                const userLat = position.coords.latitude;
                const userLng = position.coords.longitude;
                
                // Add routing control
                routingControl = L.Routing.control({
                    waypoints: [
                        L.latLng(userLat, userLng),
                        L.latLng(destLat, destLng)
                    ],
                    router: L.Routing.osrmv1({
                        serviceUrl: 'https://router.project-osrm.org/route/v1'
                    }),
                    lineOptions: {
                        styles: [{ color: '#00d4aa', opacity: 0.8, weight: 5 }]
                    },
                    createMarker: function() { return null; },
                    show: true,
                    addWaypoints: false,
                    routeWhileDragging: false
                }).addTo(map);
                
                // Calculate distance
                routingControl.on('routesfound', function(e) {
                    const routes = e.routes;
                    if (routes && routes[0]) {
                        const distance = (routes[0].summary.totalDistance / 1000).toFixed(2);
                        const duration = Math.round(routes[0].summary.totalTime / 60);
                        
                        L.popup()
                            .setLatLng([destLat, destLng])
                            .setContent(`
                                <div style="min-width: 200px;">
                                    <strong>${orderNumber}</strong><br>
                                    <span style="font-size: 12px;">Distance: <strong>${distance} km</strong></span><br>
                                    <span style="font-size: 12px;">Est. Time: <strong>${duration} min</strong></span><br>
                                    <button onclick="clearRoute()" style="margin-top: 8px; padding: 4px 8px; background: #dc2626; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 11px;">Clear Route</button>
                                </div>
                            `)
                            .openOn(map);
                    }
                });
                
                // Fit map to show route
                map.fitBounds([
                    [userLat, userLng],
                    [destLat, destLng]
                ], { padding: [50, 50] });
            },
            function(error) {
                alert('Unable to get your location. Please enable location access.');
            },
            {
                enableHighAccuracy: true,
                timeout: 10000
            }
        );
    } else {
        alert('Geolocation is not supported by your browser.');
    }
}

function clearRoute() {
    if (routingControl) {
        map.removeControl(routingControl);
        routingControl = null;
        map.closePopup();
    }
}

function copyMapUrl() {
    const url = window.location.href;
    navigator.clipboard.writeText(url).then(() => {
        alert('Map URL copied to clipboard!');
    }).catch(() => {
        prompt('Copy this URL:', url);
    });
}
</script>

<style>
.custom-order-marker {
    background: transparent !important;
    border: none !important;
}
.order-card {
    transition: all 0.2s;
}
.order-card:hover {
    transform: translateY(-2px);
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
@endsection
