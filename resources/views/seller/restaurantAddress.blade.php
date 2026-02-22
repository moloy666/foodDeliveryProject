@extends('layouts.seller')

@section('title', 'Seller Dashboard')

@section('content')

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" defer></script>


    <div class="space-y-3 max-w-xl">
        <div>
            <label for="address_line_1" class="block font-medium text-gray-700 mb-1">Address Line 1</label>
            <textarea name="address_line_1" class="w-full border border-gray-300 rounded px-3 py-2 outline-none" id="address_line_1"
                cols="30" rows="3" placeholder="Address Line 1"></textarea>
        </div>

        <div>
            <label for="address_line_2" class="block font-medium text-gray-700 mb-1">Address Line 2</label>
            <textarea name="address_line_2" class="w-full border border-gray-300 rounded px-3 py-2 outline-none" id="address_line_2"
                cols="30" rows="3" placeholder="Address Line 2"></textarea>
        </div>

        <div>
            <label for="city" class="block font-medium text-gray-700 mb-1">Locality</label>
            <input type="text" name="city" id="city" placeholder="Eg. Kolkata"
                class="w-full border border-gray-300 rounded px-3 py-2 outline-none" />
        </div>

        <div>
            <label for="state" class="block font-medium text-gray-700 mb-1">State</label>
            <input type="text" name="state" id="state" placeholder="Eg. West Bengal" value="West Bengal"
                class="w-full border border-gray-300 rounded px-3 py-2 outline-none" />
        </div>

        <div>
            <label for="country" class="block font-medium text-gray-700 mb-1">Country</label>
            <input type="text" name="country" id="country" value="India" placeholder="Eg. India"
                class="w-full border border-gray-300 rounded px-3 py-2 outline-none" />
        </div>

        <div>
            <label for="postal_code" class="block font-medium text-gray-700 mb-1">Postal Code</label>
            <input type="number" name="postal_code" id="postal_code" placeholder="Eg. 711114"
                class="w-full border border-gray-300 rounded px-3 py-2 outline-none" />
        </div>

        <div>
            <label for="latitude" class="block font-medium text-gray-700 mb-1">Latitude</label>
            <input type="text" name="latitude" id="latitude" placeholder="Eg. 88.211114"
                class="w-full border border-gray-300 rounded px-3 py-2 outline-none" />
        </div>

        <div>
            <label for="longitude" class="block font-medium text-gray-700 mb-1">Longitude</label>
            <input type="text" name="longitude" id="longitude" placeholder="Eg. 88.211114"
                class="w-full border border-gray-300 rounded px-3 py-2 outline-none" />
        </div>

        <div>
            <label class="block font-medium text-gray-700 mb-1">Pick Location</label>
            <div id="map" class="w-full h-64 rounded border"></div>
        </div>

        <div>
            <button type="submit" id="btn-submit"
                class="bg-gray-800 hover:bg-gray-900 text-white px-6 py-2 rounded font-semibold transition">
                Save Changes
            </button>
        </div>
    </div>

    <script type="module">
        import {
            httpRequest,
            showToast
        } from '/js/httpClient.js';

        let map, marker;

        function initMap(lat = 22.5726, lng = 88.3639) {
            map = L.map('map').setView([lat, lng], 15);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap'
            }).addTo(map);

            marker = L.marker([lat, lng], {
                draggable: true
            }).addTo(map);

            updateLatLngInputs(lat, lng);

            // Drag marker
            marker.on('dragend', function(e) {
                const {
                    lat,
                    lng
                } = e.target.getLatLng();
                updateLatLngInputs(lat, lng);
            });

            // Click map
            map.on('click', function(e) {
                const {
                    lat,
                    lng
                } = e.latlng;
                marker.setLatLng([lat, lng]);
                updateLatLngInputs(lat, lng);
            });
        }

        function updateLatLngInputs(lat, lng) {
            document.getElementById("latitude").value = lat.toFixed(6);
            document.getElementById("longitude").value = lng.toFixed(6);
        }

        // Get current location
        function loadCurrentLocation() {
            if (!navigator.geolocation) {
                initMap();
                return;
            }

            navigator.geolocation.getCurrentPosition(
                (position) => {
                    initMap(position.coords.latitude, position.coords.longitude);
                },
                () => {
                    initMap(); // fallback Kolkata
                }
            );
        }

        loadCurrentLocation();

        async function addRestaurantAddress() {
            try {
                const restaurantUid = "{{ $restaurantId ?? '' }}";

                const address_line_1 = document.getElementById("address_line_1").value;
                if (!address_line_1) {
                    showToast("info", "Enter address line 1");
                    return;
                }

                const city = document.getElementById("city").value;
                if (!city) {
                    showToast("info", "Enter locality");
                    return;
                }

                const state = document.getElementById("state").value;
                if (!state) {
                    showToast("info", "Enter state");
                    return;
                }

                const country = document.getElementById("country").value;
                if (!country) {
                    showToast("info", "Enter country");
                    return;
                }

                const postal_code = document.getElementById("postal_code").value;
                if (!postal_code) {
                    showToast("info", "Enter postal code");
                    return;
                }

                const latitude = document.getElementById("latitude").value;
                if (!latitude) {
                    showToast("info", "Latitude not found! Select location in map");
                    return;
                }

                const longitude = document.getElementById("longitude").value;
                if (!longitude) {
                    showToast("info", "Longitude not found! Select location in map");
                    return;
                }

                const bodyData = {
                    label: "Main Branch",
                    address_line_1,
                    address_line_2: document.getElementById("address_line_2").value,
                    city,
                    state,
                    country,
                    postal_code,
                    latitude,
                    longitude,
                    is_default: true
                };

                document.getElementById('btn-submit').disabled = true

                const res = await httpRequest(
                    `/api/restaurants/${restaurantUid}/addresses`, {
                        method: "POST",
                        body: bodyData,
                    }
                );

                if (res.statusCode === 201 || res.success) {
                    showToast("success", "Address added successfully");
                }

            } catch (err) {
                console.error(err);
            } finally {
                document.getElementById('btn-submit').disabled = false

            }
        }

        // attach to button
        document.querySelector("button[type='submit']")
            .addEventListener("click", (e) => {
                e.preventDefault();
                addRestaurantAddress();
            });
    </script>



@endsection
