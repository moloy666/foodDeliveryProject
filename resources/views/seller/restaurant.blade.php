@extends('layouts.seller')

@section('title', 'Seller Dashboard')

@section('content')
    <div class="max-w-5xl mx-auto px-2 sm:px-0">

        <!-- Restaurant Basic Details Card -->
        <div id="resto_skeleton" class="bg-white rounded-lg p-4 sm:p-6 mb-6 sm:mb-8 animate-pulse">
            <div class="h-5 w-40 bg-gray-200 rounded mb-3"></div>
            <div class="space-y-2 mb-4">
                <div class="h-4 bg-gray-200 rounded w-full"></div>
                <div class="h-4 bg-gray-200 rounded w-5/6"></div>
            </div>
            <div class="flex flex-col sm:flex-row sm:gap-6 gap-2">
                <div class="h-4 w-48 bg-gray-200 rounded"></div>
                <div class="h-4 w-36 bg-gray-200 rounded"></div>
            </div>
        </div>

        <div id="resto_content" class="bg-white rounded-lg p-4 sm:p-6 mb-6 sm:mb-8 hidden">
            <div class="font-semibold text-base sm:text-lg mb-1" id="resto-name"></div>
            <div class="text-gray-600 mb-3 text-sm" id="resto-desc"></div>
            <div class="flex flex-col sm:flex-row sm:gap-6 gap-1">
                <span class="text-sm text-gray-500">
                    <span class="font-medium">Email :</span>
                    <span id="resto-email"></span>
                </span>
                <span class="text-sm text-gray-500">
                    <span class="font-medium">Mobile :</span>
                    <span id="resto-mobile"></span>
                </span>
            </div>
        </div>


        <!-- Stats Cards -->
        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4 mb-6 sm:mb-8">
            <div class="bg-white rounded-lg shadow p-4 sm:p-5 flex flex-col items-center">
                <div class="text-xl sm:text-2xl font-semibold text-gray-800" id="stat-foods">12</div>
                <div class="text-gray-500 text-xs sm:text-sm mt-1 text-center">Total Foods</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 sm:p-5 flex flex-col items-center">
                <div class="text-xl sm:text-2xl font-semibold text-gray-800" id="stat-orders">48</div>
                <div class="text-gray-500 text-xs sm:text-sm mt-1 text-center">Total Orders</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 sm:p-5 flex flex-col items-center">
                <div class="text-xl sm:text-2xl font-semibold text-gray-800" id="stat-sales">₹15,200</div>
                <div class="text-gray-500 text-xs sm:text-sm mt-1 text-center">Total Sales</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 sm:p-5 flex flex-col items-center justify-center">
                <label class="inline-flex items-center cursor-pointer mb-1">
                    <input type="checkbox" id="onlineToggle" class="sr-only">
                    <div id="onlineToggleBg"
                        class="w-10 h-6 bg-gray-300 rounded-full transition-colors duration-200 relative">
                        <div id="onlineToggleDot"
                            class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform duration-200">
                        </div>
                    </div>
                </label>
                <div class="text-xs sm:text-sm mt-1 text-center" id="stat-online-status">Offline</div>
            </div>
        </div>

        <!-- Recent Orders Section -->
        <div class="bg-white rounded-lg shadow p-4 sm:p-5">
            <div class="flex justify-between items-center mb-3">
                <h2 class="text-base sm:text-lg font-semibold">Recent Orders</h2>
            </div>

            <!-- Desktop table header (hidden on mobile) -->
            <div class="hidden sm:grid grid-cols-4 text-xs text-gray-400 uppercase font-medium pb-2 border-b px-1">
                <span>Order</span>
                <span>Customer</span>
                <span>Time</span>
                <span class="text-right">Amount / Status</span>
            </div>

            <div id="recentOrders"></div>
        </div>
    </div>


    <script type="module">
        import {
            httpRequest
        } from '/js/httpClient.js';


        let isOnline = false;
        const onlineToggle = document.getElementById('onlineToggle');
        const onlineToggleBg = document.getElementById('onlineToggleBg');
        const onlineToggleDot = document.getElementById('onlineToggleDot');
        const statOnlineStatus = document.getElementById('stat-online-status');

        function setToggleUI(online) {
            if (online) {
                onlineToggleBg.classList.add('bg-green-400');
                onlineToggleBg.classList.remove('bg-gray-300');
                onlineToggleDot.classList.add('translate-x-4');
                statOnlineStatus.innerText = 'Online';
                statOnlineStatus.classList.add('text-green-600');
                statOnlineStatus.classList.remove('text-gray-400');
            } else {
                onlineToggleBg.classList.remove('bg-green-400');
                onlineToggleBg.classList.add('bg-gray-300');
                onlineToggleDot.classList.remove('translate-x-4');
                statOnlineStatus.innerText = 'Offline';
                statOnlineStatus.classList.remove('text-green-600');
                statOnlineStatus.classList.add('text-gray-400');
            }
        }

        onlineToggle.checked = isOnline;
        setToggleUI(isOnline);
        onlineToggle.addEventListener('change', function() {
            isOnline = onlineToggle.checked;
            setToggleUI(isOnline);
            toggleRestaurantStatus();
        });


        async function getBasicDetails() {
            try {

                const url = `/api/restaurants/{{ $restaurantId }}/basic-details`
                const res = await httpRequest(url);
                const basicDetails = res?.data?.basicDetails || null;

                document.getElementById('resto-name').innerText = basicDetails?.name || "";
                document.getElementById('resto-desc').innerText = basicDetails?.description || "";
                document.getElementById('resto-email').innerText = basicDetails?.email || "";
                document.getElementById('resto-mobile').innerText = basicDetails?.phone || "";

                setToggleUI(basicDetails?.is_open || 0);

                document.getElementById('resto_skeleton').remove();
                document.getElementById('resto_content').classList.remove('hidden');

            } catch (err) {
                console.log("Error :", err.message);
            }
        }
        getBasicDetails();



        /////////////////////////////////////////////#

        function statusClass(status) {
            switch (status) {
                case 'pending':
                    return 'bg-yellow-100 text-yellow-600';
                case 'completed':
                    return 'bg-green-100 text-green-600';
                case 'cancelled':
                    return 'bg-red-100 text-red-600';
                default:
                    return 'bg-gray-100 text-gray-600';
            }
        }

        function formatTime(dateString) {
            const date = new Date(dateString);
            return date.toLocaleString(); // you can customize
        }

        function renderOrders(orders) {
            const container = document.getElementById('recentOrders');

            if (!orders.length) {
                container.innerHTML = `<p class="text-center text-gray-400 py-4">No orders found</p>`;
                return;
            }

            let html = '';

            orders.forEach(order => {
                html += `
            <!-- Mobile View -->
            <div class="sm:hidden flex justify-between items-start py-3 border-b last:border-0 gap-2">
                <div class="flex flex-col gap-0.5">
                    <span class="font-medium text-sm">#${order.uid}</span>
                    <span class="text-sm text-gray-700">${order.user?.name || '-'}</span>
                    <span class="text-xs text-gray-400">${formatTime(order.created_at)}</span>
                </div>
                <div class="flex flex-col items-end gap-1">
                    <span class="font-semibold text-sm">₹${order.amount}</span>
                    <span class="px-2 py-0.5 rounded text-xs uppercase ${statusClass(order.status)}">
                        ${order.status}
                    </span>
                </div>
            </div>

            <!-- Desktop View -->
            <div class="hidden sm:grid grid-cols-4 items-center py-2.5 border-b last:border-0 text-sm px-1">
                <span class="font-medium">#${order.uid}</span>
                <span>${order.user?.name || '-'}</span>
                <span class="text-gray-500">${formatTime(order.created_at)}</span>
                <div class="flex justify-end items-center gap-3">
                    <span class="font-semibold">₹${order.amount}</span>
                    <span class="px-2 py-0.5 rounded text-xs uppercase ${statusClass(order.status)}">
                        ${order.status}
                    </span>
                </div>
            </div>
        `;
            });

            container.innerHTML = html;
        }

        async function getOrders() {
            try {

                const url = `/api/restaurants/{{ $restaurantId }}/orders`
                const res = await httpRequest(url);
                const orders = res?.data?.orders || [];

                renderOrders(orders);

            } catch (err) {
                console.log("Error :", err.message);
            }
        }
        getOrders();

        async function toggleRestaurantStatus() {
            try {

                const url = `/api/restaurants/{{ $restaurantId }}/toggle`
                const res = await httpRequest(url, {
                    method: "PATCH"
                });

            } catch (err) {
                console.log("Error :", err.message);
            }
        }
    </script>
@endsection
