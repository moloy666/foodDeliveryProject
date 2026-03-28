@extends('layouts.profile')

@section('title', 'My Addresses')

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

@section('content')

    <div class="max-w-5xl mx-auto">

        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold">My Addresses</h1>

            <button onclick="openModal()"
                class="bg-gray-800 hover:bg-black text-white px-5 py-2 rounded-lg text-sm font-medium transition">
                + Add New
            </button>
        </div>

        <!-- Static Address Cards -->
        <div class="grid md:grid-cols-2 gap-6" id="address_container">

        </div>
    </div>


    <!-- ===================== -->
    <!-- Address Modal -->
    <!-- ===================== -->

    <div id="addressModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50 px-4">

        <div class="bg-white w-full max-w-2xl rounded-xl shadow-lg max-h-[90vh] overflow-y-auto">

            <!-- Modal Header -->
            <div class="flex justify-between items-center border-b px-6 py-4 sticky top-0 bg-white z-10">
                <h2 class="text-lg font-semibold">Add New Address</h2>

                <button onclick="closeModal()" class="text-gray-400 hover:text-black text-xl">
                    ✕
                </button>
            </div>

            <!-- Form -->
            <form id="addressForm" class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Address Label</label>
                    <input type="text" name="label" id="address_label" placeholder="Eg. Home/Office etc."
                        class="w-full border border-gray-300 rounded-lg px-4 py-2">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Address Line 1</label>
                    <input type="text" name="address_line_1" id="address_line_1"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Address Line 2</label>
                    <input type="text" name="address_line_2" id="address_line_2"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2">
                </div>

                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">City</label>
                        <input type="text" name="city" id="city"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Postal Code</label>
                        <input type="text" name="postal_code" id="postal_code" maxlength="6" pattern="[0-9]{6}"
                            inputmode="numeric" class="w-full border border-gray-300 rounded-lg px-4 py-2"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,6);">
                    </div>
                </div>

                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">State : West Bengal</label>
                        <input type="hidden" name="state" id="state" value="West Bengal"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Country : India</label>
                        <input type="hidden" name="country" id="country" value="India"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2">
                    </div>
                </div>

                <!-- Latitude / Longitude -->
                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Latitude</label>
                        <input type="text" id="latitude" name="latitude"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Longitude</label>
                        <input type="text" id="longitude" name="longitude"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2">
                    </div>
                </div>

                <!-- Map -->
                <div>
                    <label class="block text-sm font-medium mb-2">
                        Select Location on Map
                    </label>

                    <div id="map" class="h-56 rounded"></div>

                    <div class="text-xs text-gray-500 mt-2">
                        Drag marker or click map to adjust location
                    </div>
                </div>

                <div class="text-right pt-4">
                    <button type="submit" id="btn_save"
                        class="bg-gray-800 hover:bg-black text-white px-8 py-2 rounded-lg font-semibold transition">
                        Save Address
                    </button>
                </div>

            </form>
        </div>
    </div>


@endsection


@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        let map;
        let marker;

        function openModal() {
            const modal = document.getElementById('addressModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            setTimeout(() => {
                initMap();
            }, 300);
        }

        function closeModal() {
            document.getElementById('addressModal').classList.add('hidden');
            document.getElementById('addressModal').classList.remove('flex');
        }

        function initMap() {

            if (map) {
                map.invalidateSize();
                return;
            }

            let defaultLat = 22.5726; // Kolkata fallback
            let defaultLng = 88.3639;

            map = L.map('map').setView([defaultLat, defaultLng], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap'
            }).addTo(map);

            marker = L.marker([defaultLat, defaultLng], {
                draggable: true
            }).addTo(map);

            updateInputs(defaultLat, defaultLng);

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    position => {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;

                        map.setView([lat, lng], 16);
                        marker.setLatLng([lat, lng]);

                        updateInputs(lat, lng);
                    },
                    () => console.log("Location permission denied")
                );
            }

            map.on('click', function(e) {
                marker.setLatLng(e.latlng);
                updateInputs(e.latlng.lat, e.latlng.lng);
            });

            marker.on('dragend', function(e) {
                const coords = e.target.getLatLng();
                updateInputs(coords.lat, coords.lng);
            });
        }

        function updateInputs(lat, lng) {
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
        }
    </script>

    <script type="module">
        import {
            httpRequest,
            showToast
        } from "/js/httpClient.js";


        const addressContainer = document.getElementById('address_container');

        function showAddressSkeleton(count = 2) {
            let skeleton = '';
            for (let i = 0; i < count; i++) {
                skeleton += `
                    <div class="bg-white shadow rounded-xl p-6 border animate-pulse">

                        <div class="flex justify-between mb-4">
                            <div class="h-5 w-32 bg-gray-200 rounded"></div>
                            <div class="h-4 w-16 bg-gray-200 rounded"></div>
                        </div>

                        <div class="space-y-2">
                            <div class="h-4 bg-gray-200 rounded w-full"></div>
                            <div class="h-4 bg-gray-200 rounded w-5/6"></div>
                            <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                        </div>

                        <div class="flex gap-4 mt-6">
                            <div class="h-4 w-12 bg-gray-200 rounded"></div>
                            <div class="h-4 w-14 bg-gray-200 rounded"></div>
                            <div class="h-4 w-24 bg-gray-200 rounded"></div>
                        </div>

                    </div>
                    `;
            }
            addressContainer.innerHTML = skeleton;
        }
        showAddressSkeleton()

        async function getAddresses() {
            try {
                const res = await httpRequest(`/api/users/addresses`);
                const addresses = res?.data?.addresses;
                renderAddress(addresses)
            } catch (e) {
                console.error(e);
            }
        }
        getAddresses();


        function renderAddress(addresses) {

            addressContainer.innerHTML = '';

            if (addresses.length === 0) {
                addressContainer.innerHTML = `
                    <div class="text-center text-gray-500 py-10">
                        No address available
                    </div>
                `;
                return;
            }

            addresses.forEach(address => {

                const addressCard = `
                    <div class="bg-white shadow rounded-xl p-6 relative border">

                        <h3 class="font-semibold text-lg mb-2">
                            ${address.label}
                        </h3>

                        <p class="text-gray-600 text-sm leading-relaxed">
                            ${address.address_line_1} <br>
                            ${address.city}, ${address.state} - ${address.postal_code} <br>
                            ${address.country} <br>
                            Phone: ${address.phone}
                        </p>

                        <div class="flex gap-4 mt-4 flex-wrap">
                            <button onclick="deleteAddress('${address.uid}')" 
                                class="text-sm text-red-600 hover:underline">
                                Delete
                            </button>

                        </div>
                    </div>
                    `;

                addressContainer.innerHTML += addressCard;
            });
        }

        ///////////////////////////////////////////////////////////

        const addressForm = document.getElementById('addressForm');
        addressForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(addressForm);
            addAddress(formData);
        });


        async function addAddress(formData) {

            if (document.getElementById('address_label').value == '') {
                showToast('info', "Enter address label");
                return;
            }

            if (document.getElementById('address_line_1').value == '') {
                showToast('info', "Enter address line 1");
                return;
            }

            if (document.getElementById('city').value == '') {
                showToast('info', "Enter city");
                return;
            }

            if (document.getElementById('state').value == '') {
                showToast('info', "Enter state");
                return;
            }

            if (document.getElementById('country').value == '') {
                showToast('info', "Enter country");
                return;
            }

            if (document.getElementById('latitude').value == '') {
                showToast('info', "Enter latitude");
                return;
            }

            if (document.getElementById('longitude').value == '') {
                showToast('info', "Enter longitude");
                return;
            }

            try {

                document.getElementById('btn_save').disabled = true;
                document.getElementById('btn_save').textContent = "Saving...";

                const res = await httpRequest('/api/users/addresses', {
                    method: "POST",
                    body: formData
                });

                showToast('success', res.message);
                closeModal();
                getAddresses();

            } catch (error) {
                console.log(error);

            } finally {
                document.getElementById('btn_save').disabled = false;
                document.getElementById('btn_save').textContent = "Save Address";
            }
        }
    </script>
@endpush
