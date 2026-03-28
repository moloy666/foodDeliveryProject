@extends('layouts.seller')

@section('title', 'Restaurant Menu')

@section('content')

    <div class="space-y-5 max-w-xl mx-auto px-2 sm:px-0">
        <div class="flex justify-between items-center mb-4">
            <div class="text-lg sm:text-xl font-bold">Menu</div>
            <button id="btnAdd"
                class="bg-gray-700 text-white px-3 py-1.5 sm:px-4 sm:py-2 text-sm sm:text-base rounded hover:bg-gray-800">
                + Add Item
            </button>
        </div>
        <ul id="menuContainer" class="space-y-2"></ul>
    </div>

    <!-- Add/Edit Modal -->
    <div id="modal"
        class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-end sm:items-center justify-center z-50 px-0 sm:px-4">
        <div class="bg-white rounded-t-2xl sm:rounded-lg p-5 w-full sm:max-w-md">
            <!-- Modal drag handle (mobile) -->
            <div class="flex justify-center mb-4 sm:hidden">
                <div class="w-10 h-1 bg-gray-300 rounded-full"></div>
            </div>
            <h2 id="modalTitle" class="text-lg sm:text-xl font-bold mb-4"></h2>
            <input id="menuNameInput" type="text" placeholder="Menu name"
                class="w-full border border-gray-300 rounded p-2.5 mb-4 text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-blue-300" />
            <div class="flex justify-end space-x-2">
                <button id="btnCancel"
                    class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300 text-sm sm:text-base">Cancel</button>
                <button id="btnSave"
                    class="bg-gray-700 text-white px-4 py-2 rounded hover:bg-gray-800 text-sm sm:text-base">Save</button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
    <script type="module">
        import {
            httpRequest,
            showToast
        } from '/js/httpClient.js';

        const menuContainer = document.getElementById('menuContainer');
        const modal = document.getElementById('modal');
        const modalTitle = document.getElementById('modalTitle');
        const menuNameInput = document.getElementById('menuNameInput');
        const btnAdd = document.getElementById('btnAdd');
        const btnCancel = document.getElementById('btnCancel');
        const btnSave = document.getElementById('btnSave');

        let menus = [];
        let editItemId = null;

        function showSkeleton() {
            menuContainer.innerHTML = Array(4).fill('').map(() => `
                <li class="bg-white border border-gray-100 rounded-lg p-4 flex justify-between items-center animate-pulse">
                    <div class="flex items-center gap-3 flex-1 min-w-0">
                        <div class="w-4 h-4 bg-gray-200 rounded flex-shrink-0"></div>
                        <div class="h-4 bg-gray-200 rounded w-2/3"></div>
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0 ml-3">
                        <div class="w-8 h-8 bg-gray-200 rounded-lg"></div>
                        <div class="w-8 h-8 bg-gray-200 rounded-lg"></div>
                    </div>
                </li>
            `).join('');
        }

        async function fetchMenuItems() {
            showSkeleton();
            const res = await httpRequest('/api/restaurants/{{ $restaurantId }}/menus');
            menus = res?.data?.menus || [];

            if (menus.length === 0) {
                menuContainer.innerHTML = `<p class="mt-3 text-center text-gray-500 text-sm">No menu available</p>`;
                return;
            }

            renderMenuItems();
        }

        function renderMenuItems() {
            menuContainer.innerHTML = '';
            menus.forEach((item) => {
                const li = document.createElement('li');
                li.setAttribute('data-id', item.uid);
                li.className =
                    'bg-white shadow-sm border border-gray-100 rounded-lg p-4 flex justify-between items-center cursor-move';

                li.innerHTML = `
                    <div class="flex items-center gap-3 min-w-0">
                        <span class="text-gray-300 select-none text-lg flex-shrink-0">☰</span>
                        <h3 class="text-sm sm:text-base font-semibold truncate">${item.name}</h3>
                    </div>
                    <div class="flex items-center gap-1 flex-shrink-0 ml-3">
                        <button
                            class="edit-btn p-2 rounded-lg text-blue-500 hover:bg-blue-50 transition"
                            data-id="${item.uid}" data-name="${item.name}"
                            title="Edit">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                            </svg>
                        </button>
                        <button
                            class="delete-btn p-2 rounded-lg text-red-400 hover:bg-red-50 transition"
                            data-id="${item.uid}"
                            title="Delete">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="3 6 5 6 21 6"/>
                                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                                <path d="M10 11v6"/>
                                <path d="M14 11v6"/>
                                <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                            </svg>
                        </button>
                    </div>
                `;

                menuContainer.appendChild(li);
            });

            document.querySelectorAll('.edit-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    editItemId = btn.getAttribute('data-id');
                    modalTitle.textContent = 'Edit Menu Item';
                    menuNameInput.value = btn.getAttribute('data-name');
                    modal.classList.remove('hidden');
                });
            });

            document.querySelectorAll('.delete-btn').forEach(btn => {
                btn.addEventListener('click', async () => {
                    const id = btn.getAttribute('data-id');
                    if (confirm('Delete this menu item?')) {
                        await deleteMenuItem(id);
                    }
                });
            });
        }

        btnAdd.addEventListener('click', () => {
            editItemId = null;
            modalTitle.textContent = 'Add Menu Item';
            menuNameInput.value = '';
            modal.classList.remove('hidden');
            setTimeout(() => menuNameInput.focus(), 100);
        });

        btnCancel.addEventListener('click', () => modal.classList.add('hidden'));

        // Close modal on backdrop tap (mobile friendly)
        modal.addEventListener('click', (e) => {
            if (e.target === modal) modal.classList.add('hidden');
        });

        btnSave.addEventListener('click', () => {
            if (editItemId) {
                updateMenuItem();
            } else {
                addMenuItem();
            }
        });

        // Submit on Enter key
        menuNameInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') btnSave.click();
        });

        async function addMenuItem() {
            try {
                const name = menuNameInput.value.trim();
                const res = await httpRequest('/api/restaurants/{{ $restaurantId }}/menus', {
                    method: 'POST',
                    body: {
                        name
                    }
                });
                if (res.httpStatus >= 200 && res.httpStatus < 300) {
                    showToast('success', 'Menu created successfully!');
                    modal.classList.add('hidden');
                    fetchMenuItems();
                }
            } catch (error) {
                console.log(error);
            }
        }

        async function updateMenuItem() {
            try {
                const name = menuNameInput.value.trim();
                const res = await httpRequest(`/api/restaurants/{{ $restaurantId }}/menus/${editItemId}`, {
                    method: 'PATCH',
                    body: {
                        name
                    }
                });
                if (res.httpStatus >= 200 && res.httpStatus < 300) {
                    showToast('success', 'Menu updated successfully!');
                    modal.classList.add('hidden');
                    fetchMenuItems();
                }
            } catch (error) {
                console.log(error);
            }
        }

        async function deleteMenuItem(id) {
            try {
                const res = await httpRequest(`/api/restaurants/{{ $restaurantId }}/menus/${id}`, {
                    method: 'DELETE'
                });
                if (res.httpStatus >= 200 && res.httpStatus < 300) {
                    showToast('success', 'Menu deleted successfully!');
                    fetchMenuItems();
                }
            } catch (error) {
                console.log(error);
            }
        }

        fetchMenuItems();

        let sortable = null;

        function activateSortable() {
            if (sortable) sortable.destroy();
            setTimeout(() => {
                sortable = Sortable.create(menuContainer, {
                    animation: 150,
                    handle: '.cursor-move',
                    onEnd: function() {
                        const newOrder = [...menuContainer.children].map(li => li.getAttribute(
                            'data-id'));
                        console.log('New order:', newOrder);
                    }
                });
            }, 10);
        }

        const oldRenderMenuItems = renderMenuItems;
        renderMenuItems = function() {
            oldRenderMenuItems();
            activateSortable();
        };
    </script>

@endsection
