@extends('layouts.seller')

@section('title', 'Add Food')

@section('content')

    <style>
        .menu-select-wrapper {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 8px;
            min-height: 48px;
            cursor: pointer;
            background: #fff;
            transition: border-color 0.15s;
            position: relative;
        }

        .menu-select-wrapper:focus-within {
            border-color: #6b7280;
        }

        .menu-chips {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            min-height: 32px;
            align-items: center;
        }

        .menu-chip {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #111827;
            color: #fff;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 500;
        }

        .menu-chip button {
            background: none;
            border: none;
            color: #9ca3af;
            cursor: pointer;
            padding: 0;
            line-height: 1;
            font-size: 14px;
            display: flex;
            align-items: center;
        }

        .menu-chip button:hover {
            color: #fff;
        }

        .menu-placeholder {
            color: #9ca3af;
            font-size: 14px;
            padding: 4px 4px;
        }

        .menu-dropdown {
            position: absolute;
            top: calc(100% + 6px);
            left: 0;
            right: 0;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            z-index: 50;
            overflow: hidden;
            display: none;
        }

        .menu-dropdown.open {
            display: block;
        }

        .menu-option {
            display: flex;
            align-items: center;
            padding: 10px 14px;
            font-size: 14px;
            cursor: pointer;
            transition: background 0.1s;
            justify-content: space-between;
        }

        .menu-option:hover {
            background: #f9fafb;
        }

        .menu-option.selected {
            background: #f0fdf4;
            color: #15803d;
        }

        .menu-option .check-icon {
            opacity: 0;
            color: #16a34a;
        }

        .menu-option.selected .check-icon {
            opacity: 1;
        }

        .toggle-track {
            width: 44px;
            height: 24px;
            border-radius: 999px;
            background: #e5e7eb;
            position: relative;
            transition: background 0.2s;
            cursor: pointer;
            flex-shrink: 0;
        }

        .toggle-track.on {
            background: #16a34a;
        }

        .toggle-thumb {
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: #fff;
            position: absolute;
            top: 3px;
            left: 3px;
            transition: transform 0.2s;
            pointer-events: none;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
        }

        .toggle-track.on .toggle-thumb {
            transform: translateX(20px);
        }

        .field-input {
            display: block;
            width: 100%;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 14px;
            transition: border-color 0.15s, box-shadow 0.15s;
            background: #fff;
            color: #111827;
            outline: none;
        }

        .field-input:focus {
            border-color: #6b7280;
            box-shadow: 0 0 0 3px rgba(107, 114, 128, 0.1);
        }

        .field-input::placeholder {
            color: #9ca3af;
        }

        textarea.field-input {
            resize: none;
        }
    </style>

    <div class="max-w-2xl mx-auto px-3 sm:px-0 py-6 sm:py-8">

        <!-- Header -->
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('sellerRestaurantFoodPage', ['uid' => $restaurantId]) }}"
                class="p-2 rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <line x1="19" y1="12" x2="5" y2="12" />
                    <polyline points="12 19 5 12 12 5" />
                </svg>
            </a>
            <div>
                <h2 class="text-lg sm:text-xl font-bold text-gray-900">Add New Food Item</h2>
                <p class="text-xs text-gray-400 mt-0.5">Fill in the details below to list a new dish</p>
            </div>
        </div>

        <form id="addFoodForm" class="space-y-5">

            <!-- Name -->
            <div>
                <label class="block mb-1.5 text-sm font-semibold text-gray-700">
                    Name <span class="text-red-400">*</span>
                </label>
                <input type="text" name="name" class="field-input" placeholder="e.g. Chicken Biryani" required>
            </div>

            <!-- Description -->
            <div>
                <label class="block mb-1.5 text-sm font-semibold text-gray-700">Description</label>
                <textarea name="description" class="field-input" rows="2" placeholder="Short description of the dish..."></textarea>
            </div>

            <!-- Price / Discount / Prep time -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block mb-1.5 text-sm font-semibold text-gray-700">
                        Price (₹) <span class="text-red-400">*</span>
                    </label>
                    <input type="number" name="price" class="field-input" min="1" placeholder="0" required>
                </div>
                <div>
                    <label class="block mb-1.5 text-sm font-semibold text-gray-700">Discount Price (₹)</label>
                    <input type="number" name="discount_price" class="field-input" min="1" placeholder="0">
                </div>
                <div>
                    <label class="block mb-1.5 text-sm font-semibold text-gray-700">Prep Time (min)</label>
                    <input type="number" name="preparation_time" class="field-input" min="1" placeholder="0">
                </div>
            </div>

            <!-- Toggles -->
            <div class="bg-gray-50 rounded-xl p-4 flex flex-wrap gap-5">

                <!-- Veg toggle -->
                <div class="flex items-center gap-3">
                    <div class="toggle-track" id="vegTrack"
                        onclick="toggleField('vegTrack','isVeg','vegLabel','Veg item','Non-veg')">
                        <div class="toggle-thumb"></div>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-700">Veg</div>
                        <div class="text-xs text-gray-400" id="vegLabel">Non-veg</div>
                    </div>
                    <input type="hidden" name="is_veg" id="isVeg" value="0">
                </div>

                <!-- Available toggle -->
                <div class="flex items-center gap-3">
                    <div class="toggle-track on" id="availTrack"
                        onclick="toggleField('availTrack','isAvailable','availLabel','Currently available','Unavailable')">
                        <div class="toggle-thumb"></div>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-700">Available</div>
                        <div class="text-xs text-green-600" id="availLabel">Currently available</div>
                    </div>
                    <input type="hidden" name="is_available" id="isAvailable" value="1">
                </div>

                <!-- Status -->
                <div class="flex items-center gap-3 ml-auto">
                    <label class="text-sm font-medium text-gray-700">Status</label>
                    <select name="status"
                        class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 bg-white focus:outline-none focus:border-gray-400">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

            </div>

            <!-- Currency -->
            <div>
                <label class="block mb-1.5 text-sm font-semibold text-gray-700">Currency</label>
                <input type="text" name="currency" class="field-input bg-gray-50 text-gray-400 w-24 cursor-not-allowed"
                    value="INR" readonly>
            </div>

            <!-- Tags -->
            <div>
                <label class="block mb-1.5 text-sm font-semibold text-gray-700">
                    Tags
                    <span class="text-xs font-normal text-gray-400 ml-1">comma separated</span>
                </label>
                <input type="text" name="tags" class="field-input" placeholder="e.g. paneer, spicy, bestseller">
            </div>

            <!-- Menu multi-select with search -->
            <div>
                <label class="block mb-1.5 text-sm font-semibold text-gray-700">
                    Menu
                    <span class="text-xs font-normal text-gray-400 ml-1">select one or more</span>
                </label>

                <div class="menu-select-wrapper" id="menuSelectWrapper">
                    <div class="menu-chips" id="menuChips">
                        <span class="menu-placeholder" id="menuPlaceholder">Loading menus...</span>
                    </div>
                    <div class="menu-dropdown" id="menuDropdown">
                        <!-- Search -->
                        <div class="p-2 border-b border-gray-100">
                            <input type="text" id="menuSearch" placeholder="Search menus..."
                                class="w-full text-sm px-3 py-1.5 border border-gray-200 rounded-lg outline-none focus:border-gray-400"
                                onclick="event.stopPropagation()">
                        </div>
                        <!-- Options -->
                        <div id="menuOptionsList"></div>
                    </div>
                </div>

                <!-- Hidden select carries values on submit -->
                <select name="menu" id="menuSelect" multiple class="hidden"></select>
            </div>

            <!-- Actions -->
            <div class="pt-2 flex flex-col sm:flex-row justify-end gap-3">
                <a href="{{ route('sellerRestaurantFoodPage', ['uid' => $restaurantId]) }}"
                    class="text-center text-sm text-gray-600 border border-gray-200 px-5 py-2.5 rounded-lg hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit"
                    class="bg-gray-900 hover:bg-gray-700 text-white px-6 py-2.5 rounded-lg font-semibold text-sm shadow-sm transition flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <line x1="12" y1="5" x2="12" y2="19" />
                        <line x1="5" y1="12" x2="19" y2="12" />
                    </svg>
                    Add Food
                </button>
            </div>

        </form>
    </div>

    <script type="module">
        import {
            httpRequest,
            showToast
        } from '/js/httpClient.js';

        // ── Toggle switches ───────────────────────────────────────
        window.toggleField = function(trackId, hiddenId, labelId, onText, offText) {
            const track = document.getElementById(trackId);
            const hidden = document.getElementById(hiddenId);
            const label = document.getElementById(labelId);
            const isOn = track.classList.toggle('on');
            hidden.value = isOn ? '1' : '0';
            label.textContent = isOn ? onText : offText;
            label.className = `text-xs ${isOn ? 'text-green-600' : 'text-gray-400'}`;
        };

        // ── Multi-select state ────────────────────────────────────
        const wrapper = document.getElementById('menuSelectWrapper');
        const chipsEl = document.getElementById('menuChips');
        const placeholder = document.getElementById('menuPlaceholder');
        const dropdown = document.getElementById('menuDropdown');
        const hiddenSel = document.getElementById('menuSelect');
        const searchInput = document.getElementById('menuSearch');
        const optionsList = document.getElementById('menuOptionsList');

        let selectedMenus = {}; // uid → name
        let menuSearchQuery = '';

        // ── Render chips ──────────────────────────────────────────
        function renderChips() {
            chipsEl.innerHTML = '';
            const entries = Object.entries(selectedMenus);

            if (!entries.length) {
                chipsEl.appendChild(placeholder);
                placeholder.textContent = 'Select menus...';
                return;
            }

            entries.forEach(([uid, name]) => {
                const chip = document.createElement('span');
                chip.className = 'menu-chip';
                chip.innerHTML = `${name}
                <button type="button" data-uid="${uid}" aria-label="Remove">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="3">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>`;
                chip.querySelector('button').onclick = (e) => {
                    e.stopPropagation();
                    delete selectedMenus[uid];
                    syncHiddenSelect();
                    renderChips();
                    renderDropdownOptions();
                };
                chipsEl.appendChild(chip);
            });
        }

        // ── Render dropdown options (with search filter) ──────────
        function renderDropdownOptions() {
            const opts = Array.from(hiddenSel.options).filter(opt =>
                !menuSearchQuery || opt.text.toLowerCase().includes(menuSearchQuery)
            );

            if (!opts.length) {
                optionsList.innerHTML = `
                <p class="text-center text-xs text-gray-400 py-4">No menus found</p>`;
                return;
            }

            optionsList.innerHTML = opts.map(opt => `
            <div class="menu-option ${selectedMenus[opt.value] ? 'selected' : ''}"
                 data-uid="${opt.value}" data-name="${opt.text}">
                <span>${opt.text}</span>
                <svg class="check-icon w-4 h-4" fill="none" stroke="currentColor"
                    stroke-width="2.5" viewBox="0 0 24 24">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>
            </div>
        `).join('');

            optionsList.querySelectorAll('.menu-option').forEach(el => {
                el.onclick = () => {
                    const uid = el.dataset.uid;
                    const name = el.dataset.name;
                    if (selectedMenus[uid]) {
                        delete selectedMenus[uid];
                    } else {
                        selectedMenus[uid] = name;
                    }
                    syncHiddenSelect();
                    renderChips();
                    renderDropdownOptions();
                };
            });
        }

        // ── Sync hidden <select> for form submission ──────────────
        function syncHiddenSelect() {
            Array.from(hiddenSel.options).forEach(opt => {
                opt.selected = !!selectedMenus[opt.value];
            });
        }

        // ── Search input ──────────────────────────────────────────
        searchInput.addEventListener('input', (e) => {
            menuSearchQuery = e.target.value.toLowerCase().trim();
            renderDropdownOptions();
        });
        searchInput.addEventListener('click', (e) => e.stopPropagation());

        // ── Toggle dropdown open / close ──────────────────────────
        wrapper.addEventListener('click', (e) => {
            if (e.target.closest('.menu-chip button')) return;
            const isOpening = !dropdown.classList.contains('open');
            dropdown.classList.toggle('open');
            if (isOpening) {
                setTimeout(() => searchInput.focus(), 50);
            } else {
                menuSearchQuery = '';
                searchInput.value = '';
                renderDropdownOptions();
            }
        });

        // Close on outside click
        document.addEventListener('click', (e) => {
            if (!wrapper.contains(e.target)) {
                dropdown.classList.remove('open');
                menuSearchQuery = '';
                searchInput.value = '';
                renderDropdownOptions();
            }
        });

        // ── Load menus from API ───────────────────────────────────
        async function fetchMenuItems() {
            try {
                const res = await httpRequest('/api/restaurants/{{ $restaurantId }}/menus');
                const menus = res?.data?.menus || [];

                if (!menus.length) {
                    placeholder.textContent = 'No menus available';
                    return;
                }

                hiddenSel.innerHTML = menus.map(m =>
                    `<option value="${m.uid}">${m.name}</option>`
                ).join('');

                placeholder.textContent = 'Select menus...';
                renderDropdownOptions();
            } catch (err) {
                placeholder.textContent = 'Failed to load menus';
                console.error(err);
            }
        }

        fetchMenuItems();

        // ── Form submit ───────────────────────────────────────────
        document.getElementById('addFoodForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const data = Object.fromEntries(new FormData(this));
            data.is_veg = document.getElementById('isVeg').value === '1';
            data.is_available = document.getElementById('isAvailable').value === '1';
            data.tags = data.tags?.split(',').map(s => s.trim()).filter(Boolean) || [];
            data.menu = Array.from(hiddenSel.selectedOptions).map(o => o.value);

            try {
                const res = await httpRequest('/api/restaurants/{{ $restaurantId }}/foods', {
                    method: 'POST',
                    body: data
                });
                if (res.httpStatus >= 200 && res.httpStatus < 300) {
                    showToast('success', 'Food added successfully!');
                    window.location.href = `foods/${res.data.food.uid}/images`;
                }
            } catch (err) {
                console.error(err);
            }
        });
    </script>

@endsection
