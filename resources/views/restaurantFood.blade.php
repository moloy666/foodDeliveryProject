@extends('layouts.app')

@section('title', 'Foods')

@section('content')

    <style>
        /* ── Scrollbar ── */
        html,
        body {
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        html::-webkit-scrollbar,
        body::-webkit-scrollbar {
            display: none;
        }

        body.no-scroll {
            overflow: hidden;
        }

        /* ── Floating Buttons ── */
        .sw-menu-btn {
            position: fixed;
            background: #111827;
            color: #fff;
            border: none;
            width: 52px;
            height: 52px;
            border-radius: 14px;
            font-weight: 700;
            font-size: 11px;
            letter-spacing: 0.5px;
            cursor: pointer;
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.22);
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }

        .sw-menu-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.28);
        }

        #menuFloatBtn {
            bottom: 28px;
            right: 20px;
        }

        #chatFloatBtn {
            bottom: 92px;
            right: 20px;
            font-size: 20px;
        }

        /* ── Popup Backdrop ── */
        .sw-menu-popup {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.45);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 99999;
            padding: 20px;
            backdrop-filter: blur(3px);
        }

        /* ── Menu Box ── */
        .sw-menu-box {
            max-height: 88vh;
            width: 460px;
            overflow: auto;
            background: #fff;
            color: #000;
            border-radius: 20px;
            padding: 0;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.18);
            animation: popIn 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        @keyframes popIn {
            from {
                transform: scale(0.93);
                opacity: 0;
            }

            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        .sw-menu-header {
            position: sticky;
            top: 0;
            background: #fff;
            padding: 18px 20px 14px;
            border-bottom: 1px solid #f1f1f1;
            z-index: 2;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .sw-menu-header span {
            font-size: 16px;
            font-weight: 700;
            color: #111827;
        }

        .sw-close-btn {
            background: #f3f4f6;
            border: none;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            cursor: pointer;
            color: #374151;
            transition: background 0.15s;
        }

        .sw-close-btn:hover {
            background: #e5e7eb;
        }

        /* ── Menu List ── */
        .sw-menu-list {
            padding: 8px 12px 16px;
        }

        .sw-menu-list div {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 11px 10px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            border-radius: 10px;
            color: #1f2937;
            transition: background 0.12s;
        }

        .sw-menu-list div:hover {
            background: #f9fafb;
        }

        .sw-menu-list div .menu-count {
            background: #f3f4f6;
            color: #6b7280;
            font-size: 12px;
            font-weight: 600;
            padding: 2px 9px;
            border-radius: 20px;
        }

        /* ── Food Card ── */
        .food-card {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.07);
            border: 1px solid #f1f3f5;
            overflow: hidden;
            display: flex;
            gap: 0;
            align-items: stretch;
            transition: box-shadow 0.18s, transform 0.18s;
        }

        .food-card:hover {
            box-shadow: 0 6px 22px rgba(0, 0, 0, 0.11);
            transform: translateY(-2px);
        }

        .food-card-img-wrap {
            position: relative;
            flex-shrink: 0;
        }

        .food-card img {
            height: 110px;
            width: 110px;
            object-fit: cover;
            display: block;
        }

        .food-card .meta {
            display: flex;
            flex-direction: column;
            gap: 3px;
            padding: 12px 12px 12px 14px;
            flex: 1;
            min-width: 0;
        }

        .food-card .meta .title {
            font-weight: 700;
            font-size: 14px;
            color: #111827;
            line-height: 1.25;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .food-card .meta .price-row {
            display: flex;
            align-items: baseline;
            gap: 5px;
        }

        .food-card .meta .price {
            font-weight: 700;
            font-size: 14px;
            color: #111827;
        }

        .food-card .meta .price-original {
            font-size: 12px;
            color: #9ca3af;
            text-decoration: line-through;
        }

        .food-card .meta .meta-info {
            font-size: 12px;
            color: #6b7280;
            display: flex;
            align-items: center;
            gap: 4px;
            margin-top: 2px;
        }

        .food-card .actions {
            display: flex;
            align-items: flex-end;
            padding: 0 12px 12px 0;
            flex-shrink: 0;
        }

        /* ── Buttons ── */
        .add-btn-small {
            padding: 7px 14px;
            border-radius: 10px;
            background: #111827;
            color: #fff;
            font-size: 12px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            white-space: nowrap;
            transition: background 0.15s, transform 0.1s;
        }

        .add-btn-small:hover {
            background: #1f2937;
            transform: scale(1.03);
        }

        .qty-box {
            display: inline-flex;
            align-items: center;
            gap: 0;
            background: #111827;
            border-radius: 10px;
            overflow: hidden;
        }

        .qty-box button {
            background: transparent;
            border: none;
            color: #fff;
            font-size: 16px;
            font-weight: 700;
            width: 30px;
            height: 34px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.12s;
        }

        .qty-box button:hover {
            background: rgba(255, 255, 255, 0.12);
        }

        .qty-box .qty-count {
            color: #fff;
            font-size: 13px;
            font-weight: 700;
            min-width: 26px;
            text-align: center;
        }

        .btn-disabled {
            background: #f3f4f6;
            color: #9ca3af;
            cursor: not-allowed;
            padding: 7px 12px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 600;
            border: none;
            white-space: nowrap;
        }

        /* ── Filter Chips ── */
        .filter-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 500;
            background: #f9fafb;
            color: #374151;
            cursor: pointer;
            border: 1.5px solid #e5e7eb;
            transition: all 0.15s;
        }

        .filter-chip:hover {
            border-color: #9ca3af;
            background: #f3f4f6;
        }

        .filter-chip.active {
            background: #111827;
            color: #fff;
            border-color: #111827;
        }

        .filter-chip.active .food-type {
            border-color: #fff;
        }

        .filter-chip.active .food-type::after {
            background: #fff;
        }

        /* ── Sort Select ── */
        .sort-select {
            padding: 7px 14px;
            border-radius: 10px;
            border: 1.5px solid #e5e7eb;
            font-size: 13px;
            font-weight: 500;
            background: #fff;
            color: #374151;
            cursor: pointer;
            outline: none;
            transition: border-color 0.15s;
        }

        .sort-select:focus {
            border-color: #111827;
        }

        /* ── Veg / Non-Veg Icon ── */
        .food-type {
            width: 13px;
            height: 13px;
            border: 2px solid;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 3px;
            flex-shrink: 0;
        }

        .food-type::after {
            content: "";
            width: 5px;
            height: 5px;
            border-radius: 50%;
        }

        .food-type.veg {
            border-color: #16a34a;
        }

        .food-type.veg::after {
            background: #16a34a;
        }

        .food-type.non-veg {
            border-color: #dc2626;
        }

        .food-type.non-veg::after {
            background: #dc2626;
        }

        /* ── Section Heading ── */
        .section-heading {
            font-size: 18px;
            font-weight: 700;
            color: #111827;
            padding-bottom: 10px;
            border-bottom: 2px solid #f3f4f6;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .section-heading .count-badge {
            font-size: 12px;
            font-weight: 600;
            background: #f3f4f6;
            color: #6b7280;
            padding: 3px 10px;
            border-radius: 20px;
        }

        /* ── Restaurant Info Card ── */
        .restaurant-banner {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
            border: 1px solid #f1f3f5;
            padding: 20px 22px;
            margin-bottom: 20px;
        }

        .restaurant-name {
            font-size: 22px;
            font-weight: 800;
            color: #111827;
            letter-spacing: -0.3px;
        }

        .restaurant-desc {
            font-size: 13.5px;
            color: #6b7280;
            margin-top: 4px;
            line-height: 1.5;
        }

        .restaurant-contact {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 12px;
        }

        .restaurant-contact span {
            font-size: 13px;
            color: #374151;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 5px 10px;
        }

        .address-card {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 12px 14px;
            font-size: 13px;
            color: #4b5563;
            line-height: 1.6;
            max-width: 300px;
        }

        /* ── Food Detail Popup ── */
        .food-detail-box {
            max-height: 90vh;
            width: 440px;
            overflow: auto;
            background: #fff;
            border-radius: 20px;
            padding: 0;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.18);
            animation: popIn 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .food-detail-header {
            position: sticky;
            top: 0;
            background: #fff;
            padding: 16px 18px 12px;
            border-bottom: 1px solid #f1f1f1;
            z-index: 2;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .food-detail-img {
            width: 100%;
            height: 240px;
            object-fit: cover;
        }

        .food-detail-body {
            padding: 16px 18px 20px;
        }

        .food-detail-title {
            font-size: 18px;
            font-weight: 800;
            color: #111827;
            letter-spacing: -0.2px;
            margin-bottom: 6px;
        }

        .food-detail-price {
            font-size: 17px;
            font-weight: 700;
            color: #111827;
        }

        .food-detail-desc {
            font-size: 13.5px;
            color: #6b7280;
            line-height: 1.6;
            margin-top: 10px;
        }

        .food-detail-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 14px;
        }

        /* ── Skeleton ── */
        .skeleton-card {
            background: #fff;
            border-radius: 14px;
            border: 1px solid #f1f3f5;
            overflow: hidden;
            display: flex;
            gap: 0;
            height: 110px;
        }

        .skeleton-img {
            width: 110px;
            background: #f3f4f6;
            flex-shrink: 0;
            animation: pulse 1.5s infinite;
        }

        .skeleton-body {
            flex: 1;
            padding: 12px 14px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .skeleton-line {
            background: #f3f4f6;
            border-radius: 6px;
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }

        /* ── Responsive ── */
        @media (max-width: 640px) {

            .sw-menu-box,
            .food-detail-box {
                width: 100%;
                border-radius: 16px 16px 0 0;
            }

            .sw-menu-popup {
                align-items: flex-end;
                padding: 0;
            }

            .food-card img {
                width: 90px;
                height: 90px;
            }

            .address-card {
                max-width: 100%;
            }
        }
    </style>

    <!-- ── Floating Buttons ── -->
    <button id="menuFloatBtn" class="sw-menu-btn">MENU</button>
    <button id="chatFloatBtn" class="sw-menu-btn">
        <a href="{{ route('userChatList') }}">💬</a>
    </button>

    <!-- ── Menu Popup ── -->
    <div id="swMenuPopup" class="sw-menu-popup">
        <div class="sw-menu-box">
            <div class="sw-menu-header">
                <span>Menu</span>
                <button id="swCloseMenu" class="sw-close-btn">✕</button>
            </div>
            <div id="swMenuList" class="sw-menu-list"></div>
        </div>
    </div>

    <!-- ── Food Detail Popup ── -->
    <div id="foodDetailPopup" class="sw-menu-popup">
        <div class="food-detail-box">
            <div class="food-detail-header">
                <span style="font-size:15px;font-weight:700;color:#111827;">Food Details</span>
                <button id="closeFoodPopup" class="sw-close-btn">✕</button>
            </div>
            <div id="foodDetailContent"></div>
        </div>
    </div>

    <!-- ── Breadcrumb ── -->
    <nav class="flex mb-5" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-2">
            <li class="inline-flex items-center">
                <a href="/"
                    class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-800 transition-colors">
                    <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                    </svg>
                    Home
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd" />
                    </svg>
                    <a href="/restaurants"
                        class="ml-1 text-sm font-medium text-gray-500 hover:text-gray-800 transition-colors md:ml-1.5">Restaurants</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="ml-1 text-sm font-medium text-gray-800 md:ml-1.5">{{ $restaurant->name }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- ── Restaurant Banner ── -->
    <div class="restaurant-banner">
        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-5">
            <div class="flex-1 min-w-0">
                <h1 class="restaurant-name">{{ $restaurant->name }}</h1>
                <p class="restaurant-desc">{{ $restaurant->description }}</p>
                <div class="restaurant-contact">
                    <span>📞 {{ $restaurant->phone }}</span>
                    <span>📧 {{ $restaurant->email }}</span>
                </div>
            </div>
            @if ($restaurant->addresses->isNotEmpty())
                @php $address = $restaurant->addresses->first(); @endphp
                <div class="address-card">
                    📍 {{ $address->address_line_1 }},
                    {{ $address->address_line_2 }},
                    {{ $address->city }},
                    {{ $address->state }} – {{ $address->postal_code }}
                </div>
            @endif
        </div>
    </div>

    <!-- ── Filters & Sort ── -->
    <div class="flex flex-wrap items-center gap-2 mb-6">
        <button class="filter-chip active" data-filter="all">All</button>
        <button class="filter-chip" data-filter="veg">
            <span class="food-type veg"></span> Veg
        </button>
        <button class="filter-chip" data-filter="non-veg">
            <span class="food-type non-veg"></span> Non-Veg
        </button>
        <select id="sortSelect" class="sort-select ml-auto">
            <option value="">Sort by</option>
            <option value="price_low">Price: Low → High</option>
            <option value="price_high">Price: High → Low</option>
            <option value="time_low">Time: Fastest</option>
        </select>
    </div>

    <!-- ── Food Container ── -->
    <div id="menuFoodContainer" class="space-y-8">
        <!-- Skeleton -->
        <div id="foodSkeleton" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @for ($i = 0; $i < 6; $i++)
                <div class="skeleton-card">
                    <div class="skeleton-img"></div>
                    <div class="skeleton-body">
                        <div class="skeleton-line" style="height:14px;width:70%"></div>
                        <div class="skeleton-line" style="height:12px;width:45%"></div>
                        <div class="skeleton-line" style="height:12px;width:30%;margin-top:auto"></div>
                    </div>
                </div>
            @endfor
        </div>
    </div>

    <script type="module">
        import {
            httpRequest
        } from "/js/httpClient.js";

        const restaurantId = @json($restaurantId);
        let cart = {};

        function updateCartBadge() {
            const totalItems = Object.keys(cart).length;
            document.getElementById("cartCount").innerText = totalItems;
        }

        function generateFoodCard(food) {
            const mainImage = food.images?.find(img => img.image_type === "main");
            const imageUrl = mainImage?.image_url || "/images/placeholder.jpg";
            const displayPrice = food.discount_price ?? food.price;

            return `
            <div class="food-card" id="food_${food.id}">
                <div class="food-card-img-wrap" onclick='openFoodPopup(${JSON.stringify(food).replace(/'/g, "&apos;")})' style="cursor:pointer">
                    <img src="${imageUrl}" alt="${(food.name || 'Food').replace(/"/g, '')}">
                </div>
                <div class="meta" onclick='openFoodPopup(${JSON.stringify(food).replace(/'/g, "&apos;")})' style="cursor:pointer">
                    <div class="title">${food.name}</div>
                    <div class="price-row">
                        <span class="price">₹${displayPrice}</span>
                        ${food.discount_price ? `<span class="price-original">₹${food.price}</span>` : ''}
                    </div>
                    <div class="meta-info">
                        ${food.is_veg
                            ? '<span class="food-type veg"></span> Veg'
                            : '<span class="food-type non-veg"></span> Non-Veg'}
                        ${food.preparation_time ? ` &nbsp;·&nbsp; ${food.preparation_time} min` : ''}
                    </div>
                </div>
                <div class="actions">
                    ${food.is_available
                        ? `<div class="action-controls" data-food-id="${food.uid}">
                                                   <button class="add-btn-small" data-food-id="${food.uid}">Add</button>
                                               </div>`
                        : `<button class="btn-disabled" disabled>Unavailable</button>`
                    }
                </div>
            </div>`;
        }

        function replaceWithQty(foodId) {
            document.querySelectorAll(`.action-controls[data-food-id="${foodId}"]`).forEach(wrapper => {
                wrapper.innerHTML = `
                    <div class="qty-box" data-food-id="${foodId}">
                        <button class="qty-minus" data-food-id="${foodId}" aria-label="decrease">−</button>
                        <span class="qty-count">${cart[foodId] || 0}</span>
                        <button class="qty-plus"  data-food-id="${foodId}" aria-label="increase">+</button>
                    </div>`;
            });
        }

        function updateButtonUI(foodId) {
            document.querySelectorAll(`.action-controls[data-food-id="${foodId}"]`).forEach(wrapper => {
                if (cart[foodId]) {
                    replaceWithQty(foodId);
                } else {
                    wrapper.innerHTML = `<button class="add-btn-small" data-food-id="${foodId}">Add</button>`;
                }
            });
            updateCartBadge();
        }

        async function addOrUpdateCartItem(foodId) {
            try {
                await httpRequest(`/api/restaurants/{{ $restaurantId }}/foods/${foodId}/cart`, {
                    method: "POST",
                    body: {
                        quantity: cart[foodId] || 0
                    }
                });
                fetchCartItems();
            } catch (error) {
                console.log(error);
            }
        }

        function handleAddToCart(foodId) {
            cart[foodId] = (cart[foodId] || 0) + 1;
            updateButtonUI(foodId);
            addOrUpdateCartItem(foodId);
        }

        function handleQuantityChange(foodId, delta) {
            if (!cart[foodId]) return;
            cart[foodId] += delta;
            if (cart[foodId] <= 0) delete cart[foodId];
            updateButtonUI(foodId);
            addOrUpdateCartItem(foodId);
        }

        document.addEventListener("click", function(e) {
            const t = e.target;
            if (t.matches('.add-btn-small')) {
                handleAddToCart(t.dataset.foodId);
                return;
            }
            if (t.matches('.qty-minus')) {
                handleQuantityChange(t.dataset.foodId, -1);
                return;
            }
            if (t.matches('.qty-plus')) {
                handleQuantityChange(t.dataset.foodId, 1);
                return;
            }
        });

        let allFoods = [];
        let allMenus = [];
        let menuFoodMap = {};
        let currentFilter = "all";
        let currentSort = "";
        let currentSearch = "";

        async function loadMenuFoods() {
            try {
                const [foodsRes, menusRes] = await Promise.all([
                    httpRequest(`/api/restaurants/{{ $restaurantId }}/foods`),
                    httpRequest(`/api/restaurants/{{ $restaurantId }}/menus`)
                ]);

                allFoods = foodsRes?.data?.foods || [];
                allMenus = menusRes?.data?.menus || [];

                allMenus.forEach(menu => {
                    menuFoodMap[menu.uid] = {
                        menu,
                        foods: []
                    };
                });
                allFoods.forEach(food => {
                    (food.menus || []).forEach(m => {
                        if (menuFoodMap[m.uid]) menuFoodMap[m.uid].foods.push(food);
                    });
                });

                buildSwiggyMenuPopup(allMenus, menuFoodMap);
                renderFoods();
                fetchCartItems();
            } catch (err) {
                console.error("Error loading menu foods:", err);
            }
        }

        loadMenuFoods();

        async function fetchCartItems() {
            try {
                const res = await httpRequest(`/api/cart-items`);
                const items = res?.data?.items || [];
                items.forEach(item => {
                    cart[item.food_uid] = item.quantity;
                });
                Object.keys(cart).forEach(fid => updateButtonUI(fid));
            } catch (error) {
                console.log(error);
            }
        }

        function buildSwiggyMenuPopup(menus, menuFoodMap) {
            let html = "";
            menus.forEach(menu => {
                const count = menuFoodMap[menu.uid]?.foods?.length || 0;
                if (count > 0) {
                    html += `
                        <div data-scroll="menu_${menu.uid}">
                            <span>${menu.name}</span>
                            <span class="menu-count">${count}</span>
                        </div>`;
                }
            });
            document.getElementById("swMenuList").innerHTML = html;

            document.querySelectorAll("#swMenuList div").forEach(item => {
                item.addEventListener("click", () => {
                    document.getElementById("swMenuPopup").style.display = "none";
                    const targetEl = document.getElementById(item.dataset.scroll);
                    if (targetEl) {
                        targetEl.scrollIntoView({
                            behavior: "smooth",
                            block: "start"
                        });
                    } else {
                        setTimeout(() => {
                            const t2 = document.getElementById(item.dataset.scroll);
                            if (t2) t2.scrollIntoView({
                                behavior: "smooth",
                                block: "start"
                            });
                        }, 120);
                    }
                });
            });
        }

        /* Popup open/close */
        document.getElementById("menuFloatBtn").onclick = () => {
            document.getElementById("swMenuPopup").style.display = "flex";
        };
        document.getElementById("swCloseMenu").onclick = () => {
            document.getElementById("swMenuPopup").style.display = "none";
        };
        document.getElementById("swMenuPopup").onclick = (e) => {
            if (e.target.id === "swMenuPopup") document.getElementById("swMenuPopup").style.display = "none";
        };

        window.openFoodPopup = function(food) {
            const mainImage = food.images?.find(img => img.image_type === "main");
            const imageUrl = mainImage?.image_url || "/images/placeholder.jpg";
            const displayPrice = food.discount_price ?? food.price;

            document.getElementById("foodDetailContent").innerHTML = `
                <img src="${imageUrl}" class="food-detail-img">
                <div class="food-detail-body">
                    <div class="food-detail-title">${food.name}</div>
                    <div class="food-detail-row">
                        <div>
                            <span class="food-detail-price">₹${displayPrice}</span>
                            ${food.discount_price ? `<span style="font-size:13px;color:#9ca3af;text-decoration:line-through;margin-left:5px">₹${food.price}</span>` : ''}
                        </div>
                        ${food.is_available
                            ? `<div class="action-controls" data-food-id="${food.uid}">
                                                       ${cart[food.uid]
                                                         ? `<div class="qty-box">
                                            <button class="qty-minus" data-food-id="${food.uid}">−</button>
                                            <span class="qty-count">${cart[food.uid]}</span>
                                            <button class="qty-plus"  data-food-id="${food.uid}">+</button>
                                        </div>`
                                                         : `<button class="add-btn-small" data-food-id="${food.uid}">Add To Cart</button>`
                                                       }
                                                   </div>`
                            : `<button class="btn-disabled">Unavailable</button>`
                        }
                    </div>
                    <div style="display:flex;align-items:center;gap:6px;font-size:13px;color:#6b7280;margin-top:10px">
                        ${food.is_veg
                            ? '<span class="food-type veg"></span> Vegetarian'
                            : '<span class="food-type non-veg"></span> Non-Vegetarian'}
                        ${food.preparation_time ? `<span>·</span><span>🕐 ${food.preparation_time} min</span>` : ''}
                    </div>
                    ${food.description ? `<p class="food-detail-desc">${food.description}</p>` : ''}
                </div>`;

            document.getElementById("foodDetailPopup").style.display = "flex";
            document.body.classList.add("no-scroll");
        };

        document.getElementById("closeFoodPopup").onclick = () => {
            document.getElementById("foodDetailPopup").style.display = "none";
            document.body.classList.remove("no-scroll");
        };
        document.getElementById("foodDetailPopup").onclick = (e) => {
            if (e.target.id === "foodDetailPopup") {
                document.getElementById("foodDetailPopup").style.display = "none";
                document.body.classList.remove("no-scroll");
            }
        };

        /* ── Render ── */
        function renderFoods() {
            let finalHtml = "";
            Object.values(menuFoodMap).forEach(group => {
                let foods = [...group.foods];

                if (currentSearch) {
                    foods = foods.filter(f => f.name.toLowerCase().includes(currentSearch));
                }
                if (currentFilter === "veg") foods = foods.filter(f => f.is_veg);
                if (currentFilter === "non-veg") foods = foods.filter(f => !f.is_veg);
                if (!foods.length) return;

                if (currentSort === "price_low") foods.sort((a, b) => (a.discount_price ?? a.price) - (b
                    .discount_price ?? b.price));
                if (currentSort === "price_high") foods.sort((a, b) => (b.discount_price ?? b.price) - (a
                    .discount_price ?? a.price));
                if (currentSort === "time_low") foods.sort((a, b) => (a.preparation_time ?? 999) - (b
                    .preparation_time ?? 999));

                finalHtml += `
                    <div id="menu_${group.menu.uid}">
                        <div class="section-heading">
                            <span>${group.menu.name}</span>
                            <span class="count-badge">${foods.length} items</span>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                            ${foods.map(f => generateFoodCard(f)).join("")}
                        </div>
                    </div>`;
            });

            document.getElementById("menuFoodContainer").innerHTML = finalHtml || `
                <div style="text-align:center;padding:60px 20px;color:#9ca3af">
                    <div style="font-size:40px;margin-bottom:10px">🍽️</div>
                    <div style="font-weight:600;font-size:15px">No items found</div>
                    <div style="font-size:13px;margin-top:4px">Try adjusting your filters</div>
                </div>`;
        }

        document.querySelectorAll(".filter-chip").forEach(btn => {
            btn.onclick = () => {
                document.querySelectorAll(".filter-chip").forEach(b => b.classList.remove("active"));
                btn.classList.add("active");
                currentFilter = btn.dataset.filter;
                renderFoods();
            };
        });

        document.getElementById("sortSelect").onchange = (e) => {
            currentSort = e.target.value;
            renderFoods();
        };

        document.getElementById('search')?.addEventListener('input', (e) => {
            currentSearch = e.target.value.toLowerCase().trim();
            updateSearchParam(currentSearch);
            renderFoods();
        });

        document.getElementById('searchMobile')?.addEventListener('input', (e) => {
            currentSearch = e.target.value.toLowerCase().trim();
            updateSearchParam(currentSearch);
            renderFoods();
        });

        function updateSearchParam(val) {
            const params = new URLSearchParams(window.location.search);
            if (val) params.set("search", val);
            else params.delete("search");
            window.history.replaceState({}, "", `${window.location.pathname}?${params.toString()}`);
        }
    </script>

@endsection
