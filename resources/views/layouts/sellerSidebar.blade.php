<div class="fixed bottom-0 left-0 right-0 z-40 bg-white border-t border-gray-200 md:hidden">
    <div class="flex items-center justify-around px-2 py-1">
        {{-- Home --}}
        <a href="{{ route('sellerDashboardPage') }}"
            class="flex flex-col items-center py-2 px-3 rounded-lg text-gray-500 hover:text-gray-800 transition
                {{ request()->routeIs('sellerDashboardPage') ? 'text-gray-900' : '' }}">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                <polyline points="9 22 9 12 15 12 15 22" />
            </svg>
            <span class="text-xs mt-1 font-medium">Home</span>
        </a>

        {{-- Orders --}}
        <a href="{{ route('sellerRestaurantPage', ['uid' => $restaurantId]) }}"
            class="flex flex-col items-center py-2 px-3 rounded-lg text-gray-500 hover:text-gray-800 transition
                {{ request()->routeIs('sellerRestaurantPage') ? 'text-gray-900' : '' }}">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2" />
                <rect x="9" y="3" width="6" height="4" rx="1" ry="1" />
                <line x1="9" y1="12" x2="15" y2="12" />
                <line x1="9" y1="16" x2="12" y2="16" />
            </svg>
            <span class="text-xs mt-1 font-medium">Orders</span>
        </a>

        {{-- Menu --}}
        <a href="{{ route('sellerRestaurantMenuPage', ['uid' => $restaurantId]) }}"
            class="flex flex-col items-center py-2 px-3 rounded-lg text-gray-500 hover:text-gray-800 transition
                {{ request()->routeIs('sellerRestaurantMenuPage') ? 'text-gray-900' : '' }}">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 6h18M3 12h18M3 18h18" />
            </svg>
            <span class="text-xs mt-1 font-medium">Menu</span>
        </a>

        {{-- Chats --}}
        <a href="#"
            class="flex flex-col items-center py-2 px-3 rounded-lg text-gray-500 hover:text-gray-800 transition
                {{ request()->routeIs('sellerChatsPage') ? 'text-gray-900' : '' }}">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
            </svg>
            <span class="text-xs mt-1 font-medium">Chats</span>
        </a>

        {{-- More (sidebar toggle) --}}
        <button id="sidebarToggle"
            class="flex flex-col items-center py-2 px-3 rounded-lg text-gray-500 hover:text-gray-800 transition focus:outline-none"
            aria-label="Toggle sidebar">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 6h18M3 12h18M3 18h18" />
            </svg>

            <span class="text-xs mt-1 font-medium">More</span>
        </button>

    </div>
</div>

{{-- Backdrop overlay (mobile only) --}}
<div id="sidebarBackdrop" class="fixed inset-0 bg-black bg-opacity-40 z-30 hidden md:hidden" onclick="closeSidebar()">
</div>

{{-- Main layout wrapper --}}
<div class="flex max-w-7xl mx-auto px-0 md:px-4 py-0 md:py-8 md:space-x-6">

    {{-- Sidebar --}}
    <aside id="sidebar"
        class="
            fixed top-0 left-0 h-full w-64 bg-white shadow-lg z-40 p-6
            transform -translate-x-full transition-transform duration-300 ease-in-out
            md:static md:translate-x-0 md:rounded md:shadow md:sticky md:top-16
            md:h-[calc(100vh-4rem)] md:transition-none md:block md:flex-shrink-0
        ">

        {{-- Close button (mobile only) --}}
        <div class="flex items-center justify-between mb-6 md:hidden">
            <span class="font-semibold text-gray-700">Menu</span>
            <button onclick="closeSidebar()" class="p-1 rounded hover:bg-gray-100">
                <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18" />
                    <line x1="6" y1="6" x2="18" y2="18" />
                </svg>
            </button>
        </div>

        <nav class="flex flex-col space-y-2">
            <a href="{{ route('sellerRestaurantProfilePage', ['uid' => $restaurantId]) }}" onclick="closeSidebar()"
                class="block py-2 px-3 rounded hover:bg-gray-500 hover:text-white text-sm
                    {{ request()->routeIs('sellerRestaurantProfilePage') ? 'bg-gray-800 text-white' : 'text-gray-700' }}">
                Basic Details
            </a>
            <a href="{{ route('sellerRestaurantImagePage', ['uid' => $restaurantId]) }}" onclick="closeSidebar()"
                class="block py-2 px-3 rounded hover:bg-gray-500 hover:text-white text-sm
                    {{ request()->routeIs('sellerRestaurantImagePage') ? 'bg-gray-800 text-white' : 'text-gray-700' }}">
                Images
            </a>
            <a href="{{ route('sellerRestaurantDocumentPage', ['uid' => $restaurantId]) }}" onclick="closeSidebar()"
                class="block py-2 px-3 rounded hover:bg-gray-500 hover:text-white text-sm
                    {{ request()->routeIs('sellerRestaurantDocumentPage') ? 'bg-gray-800 text-white' : 'text-gray-700' }}">
                Documents
            </a>
            <a href="{{ route('sellerRestaurantAddressPage', ['uid' => $restaurantId]) }}" onclick="closeSidebar()"
                class="block py-2 px-3 rounded hover:bg-gray-500 hover:text-white text-sm
                    {{ request()->routeIs('sellerRestaurantAddressPage') ? 'bg-gray-800 text-white' : 'text-gray-700' }}">
                Address
            </a>
            <a href="{{ route('sellerRestaurantPage', ['uid' => $restaurantId]) }}" onclick="closeSidebar()"
                class="block py-2 px-3 rounded hover:bg-gray-500 hover:text-white text-sm
                    {{ request()->routeIs('sellerRestaurantPage') ? 'bg-gray-800 text-white' : 'text-gray-700' }}">
                Dashboard
            </a>
           
            <a href="{{ route('sellerRestaurantMenuPage', ['uid' => $restaurantId]) }}" onclick="closeSidebar()"
                class="block py-2 px-3 rounded hover:bg-gray-500 hover:text-white text-sm
                    {{ request()->routeIs('sellerRestaurantMenuPage') ? 'bg-gray-800 text-white' : 'text-gray-700' }}">
                Menu
            </a>
            <a href="{{ route('sellerRestaurantFoodPage', ['uid' => $restaurantId]) }}" onclick="closeSidebar()"
                class="block py-2 px-3 rounded hover:bg-gray-500 hover:text-white text-sm
                    {{ request()->routeIs('sellerRestaurantFoodPage') ? 'bg-gray-800 text-white' : 'text-gray-700' }}">
                Foods
            </a>

            <div class="pt-2 border-t border-gray-200">
                <a href="{{ route('sellerDashboardPage') }}" onclick="closeSidebar()"
                    class="block py-2 px-3 rounded hover:bg-gray-500 hover:text-white text-sm text-gray-700">
                    ← Go Back
                </a>
            </div>
        </nav>
    </aside>

    {{-- Main content --}}
    <main class="flex-1 bg-white rounded shadow p-4 sm:p-6 min-h-[500px] w-full pb-20 md:pb-6">
        @yield('content')
    </main>

</div>

<script>
    const sidebar = document.getElementById('sidebar');
    const backdrop = document.getElementById('sidebarBackdrop');
    const menuIcon = document.getElementById('menuIcon');
    const closeIcon = document.getElementById('closeIcon');

    document.getElementById('sidebarToggle').addEventListener('click', () => {
        const isOpen = !sidebar.classList.contains('-translate-x-full');
        isOpen ? closeSidebar() : openSidebar();
    });

    function openSidebar() {
        sidebar.classList.remove('-translate-x-full');
        backdrop.classList.remove('hidden');
        menuIcon.classList.add('hidden');
        closeIcon.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeSidebar() {
        sidebar.classList.add('-translate-x-full');
        backdrop.classList.add('hidden');
        menuIcon.classList.remove('hidden');
        closeIcon.classList.add('hidden');
        document.body.style.overflow = '';
    }
</script>
