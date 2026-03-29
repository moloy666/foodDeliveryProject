@extends('layouts.profile')

@section('title', 'My Orders')

@section('content')

    <style>
        /* ── Layout ── */
        .orders-chat-layout {
            display: flex;
            height: calc(100vh - 10rem);
            gap: 0;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.07);
        }

        /* ── Left Panel ── */
        .orders-panel {
            width: 290px;
            flex-shrink: 0;
            border-right: 1px solid #f0f0f0;
            display: flex;
            flex-direction: column;
            background: #fafafa;
        }

        .orders-panel-header {
            padding: 18px 16px 14px;
            border-bottom: 1px solid #f0f0f0;
        }

        .orders-panel-header h2 {
            font-size: 16px;
            font-weight: 800;
            color: #111827;
        }

        .orders-panel-header p {
            font-size: 12px;
            color: #9ca3af;
            margin-top: 2px;
        }

        .orders-list {
            flex: 1;
            overflow-y: auto;
            padding: 8px;
        }

        .orders-list::-webkit-scrollbar {
            width: 4px;
        }

        .orders-list::-webkit-scrollbar-thumb {
            background: #e5e7eb;
            border-radius: 4px;
        }

        /* Order Item */
        .order-list-item {
            padding: 12px 10px;
            border-radius: 12px;
            cursor: pointer;
            transition: background 0.12s;
            margin-bottom: 2px;
        }

        .order-list-item:hover {
            background: #f3f4f6;
        }

        .order-list-item.active {
            background: #111827;
        }

        .order-list-item.active .oli-name,
        .order-list-item.active .oli-amount {
            color: #fff;
        }

        .order-list-item.active .oli-uid,
        .order-list-item.active .oli-items {
            color: rgba(255, 255, 255, 0.5);
        }

        .oli-row1 {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 6px;
        }

        .oli-name {
            font-size: 13.5px;
            font-weight: 700;
            color: #111827;
            line-height: 1.2;
        }

        .oli-uid {
            font-size: 10px;
            color: #9ca3af;
            margin-top: 2px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .oli-row2 {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 6px;
        }

        .oli-items {
            font-size: 11.5px;
            color: #6b7280;
        }

        .oli-amount {
            font-size: 13px;
            font-weight: 700;
            color: #111827;
        }

        .oli-badge {
            font-size: 9.5px;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 20px;
            text-transform: capitalize;
            flex-shrink: 0;
        }

        .oli-badge.pending {
            background: #fef9c3;
            color: #a16207;
        }

        .oli-badge.delivered {
            background: #dcfce7;
            color: #15803d;
        }

        .oli-badge.cancelled {
            background: #fee2e2;
            color: #b91c1c;
        }

        .oli-badge.default {
            background: #f3f4f6;
            color: #374151;
        }

        /* ── Right: Chat Panel ── */
        .chat-panel {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        /* Empty state */
        .chat-empty {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #9ca3af;
            text-align: center;
            padding: 40px;
        }

        .chat-empty .emoji {
            font-size: 48px;
            margin-bottom: 12px;
        }

        .chat-empty .title {
            font-size: 15px;
            font-weight: 600;
            color: #374151;
        }

        .chat-empty .sub {
            font-size: 13px;
            margin-top: 4px;
        }

        /* Chat inner */
        #chatUI {
            display: none;
            flex-direction: column;
            flex: 1;
            min-height: 0;
        }

        #chatUI.visible {
            display: flex;
        }

        /* Header */
        .chat-header {
            padding: 12px 18px;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
            gap: 12px;
            background: #fff;
            flex-shrink: 0;
        }

        .chat-avatar {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: #111827;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            font-weight: 700;
            flex-shrink: 0;
        }

        .chat-header-info {
            flex: 1;
            min-width: 0;
        }

        .chat-header-name {
            font-size: 15px;
            font-weight: 700;
            color: #111827;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .chat-header-sub {
            font-size: 11.5px;
            color: #9ca3af;
            margin-top: 1px;
        }

        .chat-online {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 11.5px;
            color: #6b7280;
            flex-shrink: 0;
        }

        .online-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #22c55e;
            animation: blink 2s infinite;
        }

        @keyframes blink {

            0%,
            100% {
                opacity: 1
            }

            50% {
                opacity: 0.35
            }
        }

        /* Messages area */
        .chat-body {
            flex: 1;
            overflow-y: auto;
            padding: 18px 18px 10px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            background: #f9fafb;
        }

        .chat-body::-webkit-scrollbar {
            width: 4px;
        }

        .chat-body::-webkit-scrollbar-thumb {
            background: #e5e7eb;
            border-radius: 4px;
        }

        /* Date separator */
        .date-sep {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 11px;
            color: #9ca3af;
            margin: 4px 0;
            flex-shrink: 0;
        }

        .date-sep::before,
        .date-sep::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e5e7eb;
        }

        /* Bubble */
        .bubble-row {
            display: flex;
            align-items: flex-end;
            gap: 0;
        }

        .bubble-row.sent {
            flex-direction: row-reverse;
        }

        .bubble-col {
            display: flex;
            flex-direction: column;
            max-width: 72%;
        }

        .bubble-row.sent .bubble-col {
            align-items: flex-end;
        }

        .bubble {
            padding: 10px 14px;
            border-radius: 18px;
            font-size: 13.5px;
            line-height: 1.55;
            word-break: break-word;
        }

        .bubble.recv {
            background: #fff;
            color: #111827;
            border-bottom-left-radius: 4px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.07);
        }

        .bubble.sent {
            background: #111827;
            color: #fff;
            border-bottom-right-radius: 4px;
        }

        .bubble-time {
            font-size: 10px;
            color: #9ca3af;
            margin-top: 3px;
            padding: 0 3px;
        }

        /* Typing dots */
        .typing-wrap {
            display: none;
            align-items: flex-end;
            gap: 0;
        }

        .typing-dots {
            display: flex;
            align-items: center;
            gap: 4px;
            background: #fff;
            padding: 11px 14px;
            border-radius: 18px;
            border-bottom-left-radius: 4px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.07);
        }

        .typing-dots span {
            width: 6px;
            height: 6px;
            background: #9ca3af;
            border-radius: 50%;
            animation: tdot 1.2s infinite;
        }

        .typing-dots span:nth-child(2) {
            animation-delay: 0.2s;
        }

        .typing-dots span:nth-child(3) {
            animation-delay: 0.4s;
        }

        @keyframes tdot {

            0%,
            60%,
            100% {
                transform: translateY(0);
            }

            30% {
                transform: translateY(-5px);
            }
        }

        /* Quick chips */
        .quick-chips {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
            padding: 10px 16px 2px;
            flex-shrink: 0;
            background: #fff;
            border-top: 1px solid #f3f4f6;
        }

        .qchip {
            padding: 5px 11px;
            border-radius: 20px;
            font-size: 11.5px;
            font-weight: 500;
            background: #f3f4f6;
            color: #374151;
            border: 1.5px solid #e5e7eb;
            cursor: pointer;
            white-space: nowrap;
            transition: all 0.12s;
        }

        .qchip:hover {
            background: #111827;
            color: #fff;
            border-color: #111827;
        }

        /* Input row */
        .chat-input-row {
            display: flex;
            align-items: flex-end;
            gap: 10px;
            padding: 10px 14px 14px;
            background: #fff;
            flex-shrink: 0;
        }

        .chat-input-box {
            flex: 1;
            display: flex;
            align-items: flex-end;
            background: #f3f4f6;
            border-radius: 14px;
            padding: 9px 13px;
            border: 1.5px solid transparent;
            transition: border-color 0.15s, background 0.15s;
        }

        .chat-input-box:focus-within {
            border-color: #d1d5db;
            background: #fff;
        }

        .chat-textarea {
            flex: 1;
            background: transparent;
            border: none;
            outline: none;
            font-size: 13.5px;
            color: #111827;
            resize: none;
            max-height: 100px;
            font-family: inherit;
            line-height: 1.5;
        }

        .chat-textarea::placeholder {
            color: #9ca3af;
        }

        .btn-send {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: #111827;
            border: none;
            color: #fff;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: background 0.15s, transform 0.1s;
        }

        .btn-send:hover {
            background: #1f2937;
        }

        .btn-send:active {
            transform: scale(0.92);
        }

        .btn-send:disabled {
            background: #e5e7eb;
            cursor: not-allowed;
            transform: none;
        }

        /* Skeleton */
        .sk {
            background: #ebebec;
            border-radius: 6px;
            animation: skp 1.4s infinite;
        }

        @keyframes skp {

            0%,
            100% {
                opacity: 1
            }

            50% {
                opacity: 0.4
            }
        }

        /* Bubble animation */
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Mobile */
        @media (max-width: 640px) {
            .orders-chat-layout {
                flex-direction: column;
                height: auto;
                border-radius: 14px;
            }

            .orders-panel {
                width: 100%;
                height: 190px;
                border-right: none;
                border-bottom: 1px solid #f0f0f0;
            }

            #chatUI,
            .chat-panel {
                min-height: 400px;
            }
        }
    </style>

    <div class="orders-chat-layout">

        <!-- ── Left: Order List ── -->
        <div class="orders-panel">
            <div class="orders-panel-header">
                <p>Tap an order to chat</p>
            </div>
            <div class="orders-list" id="ordersList"></div>
        </div>

        <!-- ── Right: Chat ── -->
        <div class="chat-panel">

            <!-- Placeholder -->
            <div class="chat-empty" id="chatEmpty">
                <div class="emoji">💬</div>
                <div class="title">No conversation selected</div>
                <div class="sub">Pick an order from the left to start messaging the restaurant</div>
            </div>

            <!-- Chat UI -->
            <div id="chatUI">

                <div class="chat-header">
                    <div class="chat-avatar" id="chatAvatar">?</div>
                    <div class="chat-header-info">
                        <div class="chat-header-name" id="chatName">—</div>
                        <div class="chat-header-sub" id="chatSub">—</div>
                    </div>
                    <div class="chat-online">
                        <span class="online-dot"></span> Online
                    </div>
                </div>

                <div class="chat-body" id="chatBody">
                    <div class="typing-wrap" id="typingWrap">
                        <div class="typing-dots">
                            <span></span><span></span><span></span>
                        </div>
                    </div>
                </div>

                <div class="quick-chips" id="quickChips">
                    <button class="qchip" data-msg="Where is my order?">📦 Where is my order?</button>
                    <button class="qchip" data-msg="Please add extra napkins.">🧻 Extra napkins</button>
                    <button class="qchip" data-msg="Can you make it less spicy?">🌶️ Less spicy</button>
                    <button class="qchip" data-msg="Please hurry up.">⏱️ Hurry please</button>
                    <button class="qchip" data-msg="I have a food allergy — please check ingredients carefully.">⚠️ Allergy
                        concern</button>
                </div>

                <div class="chat-input-row">
                    <div class="chat-input-box">
                        <textarea id="chatInput" class="chat-textarea" rows="1" maxlength="300" placeholder="Message the restaurant…"></textarea>
                    </div>
                    <button class="btn-send" id="btnSend">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="22" y1="2" x2="11" y2="13" />
                            <polygon points="22 2 15 22 11 13 2 9 22 2" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>


    <script type="module">
        import {
            httpRequest
        } from "/js/httpClient.js";

        let currentOrder = null;
        const msgStore = {}; // uid → [{text, sender, time}]

        const ordersList = document.getElementById('ordersList');
        const chatEmpty = document.getElementById('chatEmpty');
        const chatUI = document.getElementById('chatUI');
        const chatBody = document.getElementById('chatBody');
        const chatInput = document.getElementById('chatInput');
        const btnSend = document.getElementById('btnSend');
        const typingWrap = document.getElementById('typingWrap');

        /* ── Skeleton ── */
        ordersList.innerHTML = Array.from({
            length: 5
        }).map(() => `
            <div style="padding:12px 10px;display:flex;flex-direction:column;gap:7px">
                <div style="display:flex;justify-content:space-between">
                    <div class="sk" style="height:13px;width:55%"></div>
                    <div class="sk" style="height:16px;width:50px;border-radius:20px"></div>
                </div>
                <div class="sk" style="height:10px;width:40%"></div>
                <div style="display:flex;justify-content:space-between">
                    <div class="sk" style="height:10px;width:30%"></div>
                    <div class="sk" style="height:10px;width:20%"></div>
                </div>
            </div>`).join('');

        /* ── Fetch orders ── */
        async function fetchOrders() {
            try {
                const res = await httpRequest('/api/orders');
                renderList(res.data.orders);
            } catch (e) {
                ordersList.innerHTML =
                    `<div style="padding:20px;text-align:center;font-size:12px;color:#9ca3af">Failed to load</div>`;
            }
        }
        

        function badgeClass(s) {
            return {
                pending: 'pending',
                delivered: 'delivered',
                cancelled: 'cancelled'
            } [s] ?? 'default';
        }

        function renderList(orders) {
            if (!orders.length) {
                ordersList.innerHTML =
                    `<div style="padding:30px 16px;text-align:center;font-size:13px;color:#9ca3af">No orders yet</div>`;
                return;
            }

            ordersList.innerHTML = orders.map((o, i) => `
                <div class="order-list-item" data-i="${i}">
                    <div class="oli-row1">
                        <div>
                            <div class="oli-name">${o.restaurant.name}</div>
                            <div class="oli-uid">#${o.uid}</div>
                        </div>
                        <span class="oli-badge ${badgeClass(o.status)}">${o.status}</span>
                    </div>
                    <div class="oli-row2">
                        <span class="oli-items">${o.order_items.length} item${o.order_items.length > 1 ? 's' : ''}</span>
                        <span class="oli-amount">₹${o.amount}</span>
                    </div>
                </div>`).join('');

            ordersList.querySelectorAll('.order-list-item').forEach(el => {
                el.addEventListener('click', () => {
                    ordersList.querySelectorAll('.order-list-item').forEach(e => e.classList.remove(
                        'active'));
                    el.classList.add('active');
                    selectOrder(orders[+el.dataset.i]);
                });
            });
        }

        /* ── Select order ── */
        function selectOrder(order) {
            currentOrder = order;

            document.getElementById('chatAvatar').textContent = order.restaurant.name.charAt(0).toUpperCase();
            document.getElementById('chatName').textContent = order.restaurant.name;
            document.getElementById('chatSub').textContent = `Order #${order.uid} · ₹${order.amount}`;

            chatEmpty.style.display = 'none';
            chatUI.classList.add('visible');

            loadMessages(order.uid);
        }

        /* ── Load messages for order ── */
        function loadMessages(uid) {
            // Clear body, keep typing node
            chatBody.innerHTML = '';
            chatBody.appendChild(typingWrap);
            typingWrap.style.display = 'none';

            const msgs = msgStore[uid] || [];

            const sep = document.createElement('div');
            sep.className = 'date-sep';
            sep.textContent = msgs.length ? 'Today' : 'Start of conversation';
            chatBody.insertBefore(sep, typingWrap);

            msgs.forEach(m => insertBubble(m.text, m.sender, m.time, false));
            scrollBottom();
        }

        /* ── Insert bubble ── */
        function insertBubble(text, sender, time, animate = true) {
            const isSent = sender === 'user';
            const row = document.createElement('div');
            row.className = `bubble-row ${isSent ? 'sent' : ''}`;
            if (animate) row.style.animation = 'slideUp 0.18s ease both';

            row.innerHTML = `
                <div class="bubble-col">
                    <div class="bubble ${isSent ? 'sent' : 'recv'}">${esc(text)}</div>
                    <div class="bubble-time">${time}</div>
                </div>`;

            chatBody.insertBefore(row, typingWrap);
            scrollBottom();
        }

        function esc(s) {
            return s.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        }

        function scrollBottom() {
            chatBody.scrollTop = chatBody.scrollHeight;
        }

        function now() {
            return new Date().toLocaleTimeString([], {
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        /* ── Send ── */
        async function send() {
            const text = chatInput.value.trim();
            if (!text || !currentOrder) return;

            const uid = currentOrder.uid;
            const t = now();

            insertBubble(text, 'user', t, true);
            if (!msgStore[uid]) msgStore[uid] = [];
            msgStore[uid].push({
                text,
                sender: 'user',
                time: t
            });

            chatInput.value = '';
            autoResize();
            btnSend.disabled = true;

            try {
                await httpRequest(`/api/orders/${uid}/message`, {
                    method: 'POST',
                    body: {
                        message: text
                    }
                });

                // Simulated restaurant reply — remove/replace with real polling or websocket
                typingWrap.style.display = 'flex';
                scrollBottom();

                setTimeout(() => {
                    typingWrap.style.display = 'none';
                    const reply = 'Got it! We'
                    re on it👍 ';
                    const rt = now();
                    insertBubble(reply, 'restaurant', rt, true);
                    msgStore[uid].push({
                        text: reply,
                        sender: 'restaurant',
                        time: rt
                    });
                }, 1800);

            } catch (err) {
                const et = now();
                insertBubble('⚠️ Message failed. Please try again.', 'system', et, true);
            } finally {
                btnSend.disabled = false;
            }
        }

        chatInput.addEventListener('keydown', e => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                send();
            }
        });

        btnSend.addEventListener('click', send);

        /* ── Quick chips ── */
        document.querySelectorAll('.qchip').forEach(chip => {
            chip.addEventListener('click', () => {
                chatInput.value = chip.dataset.msg;
                autoResize();
                chatInput.focus();
            });
        });

        /* ── Auto resize textarea ── */
        function autoResize() {
            chatInput.style.height = 'auto';
            chatInput.style.height = Math.min(chatInput.scrollHeight, 100) + 'px';
        }
        chatInput.addEventListener('input', autoResize);

        fetchOrders();
    </script>

@endsection
