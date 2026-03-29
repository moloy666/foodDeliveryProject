@extends('layouts.app')

@section('title', 'Cart')


<style>
    :root {
        --cream: #faf8f4;
        --ink: #1a1714;
        --muted: #7a736b;
        --border: #ece8e1;
        --accent: #c8522a;
        --accent-lt: #f4ebe5;
        --green: #2d7a4f;
        --green-lt: #e6f4ed;
        --card: #ffffff;
        --shadow: 0 2px 16px rgba(26, 23, 20, .07);
        --radius: 14px;
    }

    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    body {
        background: var(--cream);
        font-family: 'DM Sans', sans-serif;
        color: var(--ink);
        min-height: 100vh;
    }

    /* ── PAGE WRAPPER ─────────────────────────────────── */
    .cart-page {
        max-width: 1100px;
        margin: 0 auto;
        padding: 28px 20px 120px;
    }

    .page-title {
        font-family: 'Playfair Display', Georgia, serif;
        font-size: clamp(1.6rem, 4vw, 2.2rem);
        font-weight: 600;
        letter-spacing: -.02em;
        margin-bottom: 28px;
    }

    .page-title span {
        color: var(--accent);
    }

    .cart-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 20px;
    }

    @media (min-width: 768px) {
        .cart-grid {
            grid-template-columns: 340px 1fr;
            align-items: start;
        }
    }

    /* ── CARD ─────────────────────────────────────────── */
    .card {
        background: var(--card);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        border: 1px solid var(--border);
        overflow: hidden;
    }

    .card-header {
        padding: 18px 20px 14px;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .card-header h3 {
        font-family: 'Playfair Display', serif;
        font-size: 1rem;
        font-weight: 600;
        letter-spacing: -.01em;
    }

    .card-body {
        padding: 16px 20px;
    }

    /* ── DELIVERY / ADDRESS ───────────────────────────── */
    #deliverySection {
        display: flex;
        flex-direction: column;
        gap: 0;
    }

    .addr-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 14px 20px;
        border-bottom: 1px solid var(--border);
        cursor: pointer;
        transition: background .15s;
    }

    .addr-item:last-child {
        border-bottom: none;
    }

    .addr-item:hover {
        background: var(--cream);
    }

    .addr-item.selected {
        background: var(--accent-lt);
    }

    .addr-radio {
        margin-top: 3px;
        accent-color: var(--accent);
        width: 16px;
        height: 16px;
        flex-shrink: 0;
    }

    .addr-label {
        font-weight: 600;
        font-size: .85rem;
        margin-bottom: 2px;
    }

    .addr-sub {
        font-size: .8rem;
        color: var(--muted);
        line-height: 1.45;
    }

    .addr-badge {
        font-size: .65rem;
        font-weight: 600;
        letter-spacing: .06em;
        text-transform: uppercase;
        background: var(--accent);
        color: #fff;
        border-radius: 4px;
        padding: 2px 6px;
        margin-left: 6px;
    }

    .add-addr-btn {
        margin: 14px 20px 20px;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: .85rem;
        font-weight: 500;
        color: var(--accent);
        background: none;
        border: 1.5px dashed var(--accent);
        border-radius: 8px;
        padding: 10px 16px;
        cursor: pointer;
        width: calc(100% - 40px);
        transition: background .15s;
    }

    .add-addr-btn:hover {
        background: var(--accent-lt);
    }

    /* ── RESTAURANT TAG ───────────────────────────────── */
    #restaurantInfo {
        display: none;
        padding: 14px 20px;
        background: var(--card);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        border: 1px solid var(--border);
        align-items: center;
        gap: 12px;
    }

    #restaurantInfo.visible {
        display: flex;
    }

    .rest-icon {
        width: 40px;
        height: 40px;
        background: var(--accent-lt);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }

    .rest-meta {
        flex: 1;
        min-width: 0;
    }

    .rest-name {
        font-weight: 600;
        font-size: .95rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .rest-hint {
        font-size: .75rem;
        color: var(--muted);
    }

    /* ── CART ITEMS ───────────────────────────────────── */
    #cartItemsContainer {
        display: none;
    }

    #cartItemsContainer.visible {
        display: block;
    }

    .cart-item {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 16px 0;
        border-bottom: 1px solid var(--border);
        animation: fadeSlideIn .25s ease both;
    }

    .cart-item:last-child {
        border-bottom: none;
    }

    @keyframes fadeSlideIn {
        from {
            opacity: 0;
            transform: translateY(6px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .item-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: var(--green);
        flex-shrink: 0;
        margin-top: 2px;
    }

    .item-info {
        flex: 1;
        min-width: 0;
    }

    .item-name {
        font-weight: 500;
        font-size: .9rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .item-qty {
        font-size: .78rem;
        color: var(--muted);
        margin-top: 2px;
    }

    .item-price {
        font-weight: 600;
        font-size: .9rem;
        flex-shrink: 0;
    }

    .remove-btn {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        border: 1px solid var(--border);
        background: #fff;
        color: var(--muted);
        font-size: .85rem;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        flex-shrink: 0;
        transition: border-color .15s, color .15s, background .15s;
    }

    .remove-btn:hover {
        border-color: var(--accent);
        color: var(--accent);
        background: var(--accent-lt);
    }

    .cart-empty {
        text-align: center;
        padding: 48px 20px;
        color: var(--muted);
    }

    .cart-empty-icon {
        font-size: 2.5rem;
        margin-bottom: 12px;
    }

    .cart-empty p {
        font-size: .9rem;
    }

    /* ── SKELETON ─────────────────────────────────────── */
    @keyframes shimmer {
        0% {
            background-position: -600px 0;
        }

        100% {
            background-position: 600px 0;
        }
    }

    .skeleton-line {
        border-radius: 6px;
        background: linear-gradient(90deg, #ede9e2 25%, #f6f3ee 50%, #ede9e2 75%);
        background-size: 600px 100%;
        animation: shimmer 1.4s infinite;
    }

    .skel-row {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 16px 0;
        border-bottom: 1px solid var(--border);
    }

    .skel-row:last-child {
        border-bottom: none;
    }

    /* ── SUMMARY / CHECKOUT ───────────────────────────── */
    .summary-card {
        background: var(--card);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        border: 1px solid var(--border);
        overflow: hidden;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 14px 20px;
        font-size: .88rem;
        border-bottom: 1px solid var(--border);
    }

    .summary-row.total {
        font-weight: 700;
        font-size: 1rem;
        border-bottom: none;
    }

    .summary-row label {
        color: var(--muted);
    }

    .checkout-btn {
        display: block;
        width: 100%;
        background: var(--ink);
        color: #fff;
        border: none;
        padding: 16px 20px;
        font-family: 'DM Sans', sans-serif;
        font-size: .92rem;
        font-weight: 600;
        letter-spacing: .01em;
        cursor: pointer;
        transition: background .18s, transform .1s;
        text-align: center;
        text-decoration: none;
    }

    .checkout-btn:hover:not(:disabled) {
        background: #2e2a27;
    }

    .checkout-btn:active:not(:disabled) {
        transform: scale(.99);
    }

    .checkout-btn:disabled {
        background: #c5c0b9;
        cursor: not-allowed;
    }

    .login-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    /* ── MOBILE STICKY FOOTER ─────────────────────────── */
    .mobile-summary {
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: var(--ink);
        color: #fff;
        padding: 14px 20px;
        gap: 16px;
        z-index: 100;
        box-shadow: 0 -4px 20px rgba(0, 0, 0, .15);
    }

    .mobile-summary-info {
        display: flex;
        flex-direction: column;
    }

    .mobile-summary-items {
        font-size: .75rem;
        opacity: .7;
    }

    .mobile-summary-total {
        font-weight: 700;
        font-size: 1.05rem;
    }

    .mobile-summary-btn {
        background: var(--accent);
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 10px 20px;
        font-family: 'DM Sans', sans-serif;
        font-weight: 600;
        font-size: .88rem;
        cursor: pointer;
        white-space: nowrap;
        transition: background .15s;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .mobile-summary-btn:disabled {
        background: #555;
        cursor: not-allowed;
    }

    .mobile-summary-btn:hover:not(:disabled) {
        background: #b0441f;
    }

    @media (min-width: 768px) {
        .mobile-summary {
            display: none;
        }
    }

    /* ── MODAL ────────────────────────────────────────── */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(26, 23, 20, .55);
        backdrop-filter: blur(4px);
        z-index: 9999;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .modal-overlay.open {
        display: flex;
    }

    .modal-box {
        background: var(--card);
        border-radius: 18px;
        width: 100%;
        max-width: 440px;
        overflow: hidden;
        box-shadow: 0 24px 60px rgba(0, 0, 0, .2);
        animation: modalPop .22s ease;
    }

    @keyframes modalPop {
        from {
            opacity: 0;
            transform: scale(.96) translateY(8px);
        }

        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }

    .modal-header {
        padding: 18px 20px;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .modal-header h3 {
        font-family: 'Playfair Display', serif;
        font-size: 1.05rem;
        font-weight: 600;
    }

    .modal-close {
        width: 30px;
        height: 30px;
        background: var(--cream);
        border: 1px solid var(--border);
        border-radius: 50%;
        cursor: pointer;
        font-size: .85rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background .15s;
    }

    .modal-close:hover {
        background: var(--border);
    }

    .modal-body {
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .field-label {
        font-size: .75rem;
        font-weight: 600;
        letter-spacing: .04em;
        text-transform: uppercase;
        color: var(--muted);
        margin-bottom: 4px;
    }

    .field-group {
        display: flex;
        flex-direction: column;
    }

    .modal-input {
        border: 1.5px solid var(--border);
        border-radius: 8px;
        padding: 10px 14px;
        font-family: 'DM Sans', sans-serif;
        font-size: .88rem;
        color: var(--ink);
        background: var(--cream);
        outline: none;
        transition: border-color .15s;
    }

    .modal-input:focus {
        border-color: var(--accent);
    }

    #map {
        height: 200px;
        border-radius: 10px;
        border: 1px solid var(--border);
        overflow: hidden;
    }

    .save-addr-btn {
        background: var(--green);
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 13px;
        font-family: 'DM Sans', sans-serif;
        font-weight: 600;
        font-size: .92rem;
        cursor: pointer;
        width: 100%;
        transition: background .15s;
    }

    .save-addr-btn:hover {
        background: #245f3d;
    }
</style>

@section('content')

    <div class="cart-page">
        <h1 class="page-title">Your <span>Cart</span></h1>

        <div class="cart-grid">

            <!-- ═══════════ LEFT: DELIVERY ═══════════ -->
            <div>
                <div class="card">
                    <div class="card-header">
                        <h3>Deliver to</h3>
                    </div>
                    <div id="deliverySection">
                        <!-- Skeleton -->
                        <div class="addr-item" id="addrSkeleton">
                            <div class="skeleton-line" style="width:16px;height:16px;border-radius:50%;flex-shrink:0"></div>
                            <div style="flex:1">
                                <div class="skeleton-line" style="height:12px;width:60%;margin-bottom:6px"></div>
                                <div class="skeleton-line" style="height:10px;width:85%"></div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>

            <!-- ═══════════ RIGHT: CART ═══════════ -->
            <div style="display:flex;flex-direction:column;gap:16px;">

                <!-- Restaurant tag -->
                <div id="restaurantInfo" class="card">
                    <div style="padding:14px 20px;display:flex;align-items:center;gap:12px;">
                        <div class="rest-icon">🍴</div>
                        <div class="rest-meta">
                            <div class="rest-name" id="restaurantName"></div>
                            <div class="rest-hint">Items from this restaurant</div>
                        </div>
                    </div>
                </div>

                <!-- Cart items -->
                <div class="card">
                    <div class="card-header">
                        <h3>Order Items</h3>
                        <span id="itemCount" style="font-size:.8rem;color:var(--muted);font-weight:500">0 items</span>
                    </div>

                    <!-- Skeleton -->
                    <div id="cartSkeleton" style="padding:0 20px;">
                        <div class="skel-row">
                            <div class="skeleton-line" style="width:8px;height:8px;border-radius:50%"></div>
                            <div style="flex:1">
                                <div class="skeleton-line" style="height:12px;width:55%;margin-bottom:6px"></div>
                                <div class="skeleton-line" style="height:10px;width:35%"></div>
                            </div>
                            <div class="skeleton-line" style="height:12px;width:44px"></div>
                        </div>
                        <div class="skel-row">
                            <div class="skeleton-line" style="width:8px;height:8px;border-radius:50%"></div>
                            <div style="flex:1">
                                <div class="skeleton-line" style="height:12px;width:45%;margin-bottom:6px"></div>
                                <div class="skeleton-line" style="height:10px;width:28%"></div>
                            </div>
                            <div class="skeleton-line" style="height:12px;width:44px"></div>
                        </div>
                    </div>

                    <!-- Items -->
                    <div id="cartItemsContainer" style="padding:0 20px;"></div>
                </div>

                <!-- Summary — desktop only -->
                <div class="summary-card" style="display:none" id="desktopSummary">
                    <div class="summary-row">
                        <label>Subtotal</label>
                        <span id="subtotalAmt">₹0</span>
                    </div>
                    <div class="summary-row">
                        <label>Delivery fee</label>
                        <span style="color:var(--green);font-weight:500">Free</span>
                    </div>
                    <div class="summary-row total">
                        <label style="color:var(--ink)">Total</label>
                        <span id="cartTotal">₹0</span>
                    </div>

                    @if ($isLoggedIn)
                        <button id="checkoutBtn" class="checkout-btn" disabled>
                            Proceed to Checkout →
                        </button>
                    @else
                        <a href="{{ route('loginPage') }}?cart=true" class="checkout-btn login-btn">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" />
                                <polyline points="10 17 15 12 10 7" />
                                <line x1="15" y1="12" x2="3" y2="12" />
                            </svg>
                            Login to continue
                        </a>
                    @endif
                </div>

            </div>
        </div>
    </div>

    <!-- ═══════════ MOBILE STICKY FOOTER ═══════════ -->
    <div class="mobile-summary">
        <div class="mobile-summary-info">
            <span class="mobile-summary-items" id="mobileItemCount">0 items</span>
            <span class="mobile-summary-total" id="mobileTotalAmt">₹0</span>
        </div>
        @if ($isLoggedIn)
            <button id="mobileCheckoutBtn" class="mobile-summary-btn" disabled>
                Checkout →
            </button>
        @else
            <a href="{{ route('loginPage') }}?cart=true" class="mobile-summary-btn">Login →</a>
        @endif
    </div>

    <!-- ═══════════ ADD ADDRESS MODAL ═══════════ -->
    <div id="addressModal" class="modal-overlay">
        <div class="modal-box">
            <div class="modal-header">
                <h3>Add Delivery Address</h3>
                <button class="modal-close" onclick="closeAddressModal()">✕</button>
            </div>
            <div class="modal-body">
                <div class="field-group">
                    <div class="field-label">Address line</div>
                    <input id="addrLine" class="modal-input" placeholder="House / flat / street">
                </div>
                <div class="field-group">
                    <div class="field-label">Locality</div>
                    <input id="addrLocality" class="modal-input" placeholder="Locality / area">
                </div>
                <div class="field-group">
                    <div class="field-label">Pincode</div>
                    <input id="addrPincode" class="modal-input" placeholder="6-digit pincode">
                </div>
                <div id="map"></div>
                <button onclick="saveDemoAddress()" class="save-addr-btn">📍 Save Address</button>
            </div>
        </div>
    </div>

    <script type="module">
        import {
            httpRequest
        } from "/js/httpClient.js";

        const isLoggedIn = @json($isLoggedIn);

        let selectedAddressId = null;
        let hasCartItems = false;

        /* ─── ADDRESSES ────────────────────────────────── */
        async function fetchAddresses() {
            const res = await httpRequest(`/api/users/addresses`);
            const addresses = res.data.addresses || [];
            renderDelivery(addresses);
        }
        fetchAddresses();

        function renderDelivery(addresses) {
            const el = document.getElementById('deliverySection');

            if (!addresses.length) {
                el.innerHTML =
                    `<p style="padding:20px;color:var(--muted);font-size:.85rem">No saved addresses. Add one below.</p>`;
                return;
            }

            el.innerHTML = addresses.map((addr, i) => `
      <label class="addr-item ${i === 0 ? 'selected' : ''}" onclick="selectAddr(this)">
        <input type="radio" name="address" class="addr-radio" value="${addr.uid}" ${addr.is_default ? 'checked' : ''}>
        <div style="flex:1;min-width:0">
          <div class="addr-label">
            ${addr.label}
            ${addr.is_default ? '<span class="addr-badge">Default</span>' : ''}
          </div>
          <div class="addr-sub">${addr.address_line_1}, ${addr.city} – ${addr.postal_code}</div>
        </div>
      </label>
    `).join('');

            document.querySelectorAll('input[name="address"]').forEach(radio => {
                radio.onchange = e => {
                    selectedAddressId = e.target.value;
                    document.querySelectorAll('.addr-item').forEach(l => l.classList.remove('selected'));
                    e.target.closest('.addr-item').classList.add('selected');
                    updateCheckoutState();
                };
            });

            if (!selectedAddressId && addresses.length) {
                selectedAddressId = addresses[0].uid;
            }
        }

        window.selectAddr = (label) => {
            const radio = label.querySelector('input[type=radio]');
            radio.checked = true;
            selectedAddressId = radio.value;
            document.querySelectorAll('.addr-item').forEach(l => l.classList.remove('selected'));
            label.classList.add('selected');
            updateCheckoutState();
        };

        /* ─── CART ─────────────────────────────────────── */
        function cartItemRow(item) {
            const price = item.food.discount_price ?? item.food.price;
            return `
      <div class="cart-item">
        <div class="item-dot"></div>
        <div class="item-info">
          <div class="item-name">${item.food.name}</div>
          <div class="item-qty">₹${price} × ${item.quantity}</div>
        </div>
        <span class="item-price">₹${price * item.quantity}</span>
        <button class="remove-btn" data-uid="${item.uid}" title="Remove">✕</button>
      </div>`;
        }

        let restaurantId = null;

        async function fetchCart() {
            const res = await httpRequest(`/api/cart-items`);
            const items = res.data.items || [];
            const restaurant = res.data.restaurant;

            hasCartItems = items.length > 0;

            let html = '';
            let total = 0;

            items.forEach(i => {
                html += cartItemRow(i);
                total += (i.food.discount_price ?? i.food.price) * i.quantity;
            });

            const container = document.getElementById('cartItemsContainer');
            container.innerHTML = html || `
      <div class="cart-empty">
        <div class="cart-empty-icon">🛒</div>
        <p>Your cart is empty</p>
      </div>`;

            container.classList.add('visible');
            document.getElementById('cartSkeleton').style.display = 'none';

            const fmt = `₹${total.toLocaleString('en-IN')}`;
            document.getElementById('cartTotal').textContent = fmt;
            document.getElementById('subtotalAmt').textContent = fmt;
            document.getElementById('itemCount').textContent = `${items.length} item${items.length !== 1 ? 's' : ''}`;

            // Mobile footer
            document.getElementById('mobileItemCount').textContent =
                `${items.length} item${items.length !== 1 ? 's' : ''}`;
            document.getElementById('mobileTotalAmt').textContent = fmt;

            if (restaurant) {
                restaurantId = restaurant.uid;
                document.getElementById('restaurantName').textContent = restaurant.name;
                document.getElementById('restaurantInfo').classList.add('visible');
            }

            document.getElementById('desktopSummary').style.display = 'block';
            updateCheckoutState();
        }
        fetchCart();

        document.addEventListener('click', e => {
            const btn = e.target.closest('.remove-btn');
            if (btn) removeCartItem(btn.dataset.uid);
        });

        async function removeCartItem(itemId) {
            try {
                await httpRequest(`/api/cart-items/${itemId}`, {
                    method: 'DELETE'
                });
                fetchCart();
            } catch (err) {
                console.error(err);
            }
        }

        /* ─── CHECKOUT STATE ────────────────────────────── */
        function updateCheckoutState() {
            const enabled = isLoggedIn && hasCartItems && !!selectedAddressId;
            const btn = document.getElementById('checkoutBtn');
            const mBtn = document.getElementById('mobileCheckoutBtn');
            if (btn) btn.disabled = !enabled;
            if (mBtn) mBtn.disabled = !enabled;
        }

        /* ─── MAP ───────────────────────────────────────── */
        let map, marker, lat, lng;

        window.openAddressModal = () => {
            document.getElementById('addressModal').classList.add('open');
            setTimeout(initMap, 250);
        };
        window.closeAddressModal = () => {
            document.getElementById('addressModal').classList.remove('open');
        };
        document.getElementById('addressModal').addEventListener('click', e => {
            if (e.target === e.currentTarget) closeAddressModal();
        });

        function initMap() {
            if (map) {
                map.invalidateSize();
                return;
            }
            navigator.geolocation.getCurrentPosition(pos => {
                lat = pos.coords.latitude;
                lng = pos.coords.longitude;
                map = L.map('map').setView([lat, lng], 15);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap'
                }).addTo(map);
                marker = L.marker([lat, lng], {
                    draggable: true
                }).addTo(map);
                marker.on('dragend', e => {
                    lat = e.target.getLatLng().lat;
                    lng = e.target.getLatLng().lng;
                });
            }, () => {
                lat = 22.5726;
                lng = 88.3639; // Kolkata fallback
                map = L.map('map').setView([lat, lng], 13);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap'
                }).addTo(map);
                marker = L.marker([lat, lng], {
                    draggable: true
                }).addTo(map);
                marker.on('dragend', e => {
                    lat = e.target.getLatLng().lat;
                    lng = e.target.getLatLng().lng;
                });
            });
        }

        window.saveDemoAddress = () => {
            closeAddressModal();
            fetchAddresses();
        };

        /* ─── CHECKOUT ──────────────────────────────────── */
        const doCheckout = async () => {
            const res = await httpRequest(`/api/orders/create`, {
                method: 'POST',
                body: {
                    address_id: selectedAddressId,
                    restaurant_id: restaurantId
                }
            });
            const {
                key,
                amount,
                currency,
                order_id
            } = res.data.payload;
            new Razorpay({
                key,
                amount,
                currency,
                order_id,
                handler: verifyPayment
            }).open();
        };

        const checkoutBtn = document.getElementById('checkoutBtn');
        const mobileCheckoutBtn = document.getElementById('mobileCheckoutBtn');
        if (checkoutBtn) checkoutBtn.onclick = doCheckout;
        if (mobileCheckoutBtn) mobileCheckoutBtn.onclick = doCheckout;

        async function verifyPayment(data) {
            try {
                const res = await httpRequest(`/api/orders/verify-payment`, {
                    method: 'POST',
                    body: {
                        order_id: data.razorpay_order_id,
                        payment_id: data.razorpay_payment_id,
                        signature: data.razorpay_signature
                    }
                });
                if (res?.success) location.href = @json(route('orderPage'));
            } catch (err) {
                console.error(err);
            }
        }
    </script>

@endsection
