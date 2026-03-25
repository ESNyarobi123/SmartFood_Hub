/**
 * Shared GPS Location Module
 * Used by both cyber/menu.blade.php and food/custom.blade.php checkout modals
 */

const GPS_ICON_SVG = `<svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
</svg>`;

const SPINNER_SVG = '<svg class="w-5 h-5 inline-block animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>';

/**
 * Get the current GPS location and populate delivery form fields.
 *
 * Expects these element IDs in the DOM:
 *   - gps-btn         (button)
 *   - gps-status      (status text element)
 *   - delivery-address (textarea)
 *   - delivery-lat    (hidden input)
 *   - delivery-lng    (hidden input)
 *
 * @param {boolean} isAutoCapture - If true, suppress button loading state and show minimal errors
 * @param {string}  accentColor   - CSS color for the "getting location" status text (default: #00d4aa)
 */
window.getCurrentLocation = function (isAutoCapture = false, accentColor = '#00d4aa') {
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
        gpsBtn.innerHTML = SPINNER_SVG + ' Getting location...';
    }
    gpsStatus.textContent = 'Getting your location...';
    gpsStatus.style.color = accentColor;
    gpsStatus.className = 'text-xs mt-1';

    navigator.geolocation.getCurrentPosition(
        function (position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;

            // Store coordinates
            document.getElementById('delivery-lat').value = lat;
            document.getElementById('delivery-lng').value = lng;

            // Show coordinates
            gpsStatus.textContent = `Location captured (${lat.toFixed(6)}, ${lng.toFixed(6)}). Please enter your full address below.`;
            gpsStatus.className = 'text-xs text-green-500 mt-1';
            gpsStatus.style.color = '';

            if (!addressInput.value) {
                addressInput.placeholder = 'Enter your delivery address (coordinates captured)';
            }

            // Reset button
            if (gpsBtn) {
                gpsBtn.disabled = false;
                gpsBtn.innerHTML = GPS_ICON_SVG + ' Retry GPS';
            }
        },
        function (error) {
            // Reset button
            if (gpsBtn) {
                gpsBtn.disabled = false;
                gpsBtn.innerHTML = GPS_ICON_SVG + (isAutoCapture ? ' Get GPS' : ' Retry GPS');
            }

            gpsStatus.style.color = '';

            if (!isAutoCapture) {
                let errorMsg = 'Unable to get your location. ';
                switch (error.code) {
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
                gpsStatus.textContent = 'Click "Get GPS" button if you want to share your location.';
                gpsStatus.className = 'text-xs text-[#6b6b6b] mt-1';
            }
        },
        {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 0,
        }
    );
};
