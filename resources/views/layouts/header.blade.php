<header class="sticky top-0 z-50 bg-white shadow px-4 py-3" x-data="{ mobileSearchOpen: false }">

    <div class="max-w-7xl mx-auto flex items-center justify-between gap-4">

        <!-- Logo -->
        <div class="text-xl font-semibold shrink-0">
            <a href="{{ url('/') }}">FulBite</a>
        </div>

        <!-- Desktop Search -->
        <div class="hidden sm:flex flex-1 max-w-xl mx-auto gap-2">

            <!-- Search -->
            <div class="relative flex-1">
                <input type="text" id="search" placeholder="Search restaurants or food..."
                    class="w-full border border-gray-300 rounded px-3 py-2 pr-9
                           focus:outline-none focus:ring-1 focus:ring-gray-500" />

                <!-- Clear -->
                <button type="button" id="clearSearch"
                    class="absolute right-2 top-1/2 -translate-y-1/2
                           text-gray-400 hover:text-gray-700 hidden">
                    ✕
                </button>
            </div>
        </div>

        <!-- Right Section -->
        <div class="flex items-center gap-3 shrink-0">

            <!-- Mobile Search Icon -->
            <button class="sm:hidden text-gray-600" @click="mobileSearchOpen = !mobileSearchOpen">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-4.35-4.35M16 10a6 6 0 11-12 0 6 6 0 0112 0z" />
                </svg>
            </button>

            <!-- Auth Dropdown -->
            <div class="relative z-[9999]" x-data="{ open: false }">
                @if ($isLoggedIn)
                    <button @click="open = !open"
                        class="flex items-center gap-2 text-gray-700 hover:text-blue-600 focus:outline-none">
                        <span class="hidden sm:inline">
                            {{ $authUser['name'] ?? 'User' }}
                        </span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-cloak x-show="open" @click.away="open = false" x-transition
                        class="absolute right-0 mt-2 w-40 bg-white border rounded shadow-lg">
                        <a href="{{ route('profilePage') }}" class="block px-4 py-2 hover:bg-gray-100">
                            Profile
                        </a>
                        <button id="btnLogout" class="w-full text-left px-4 py-2 hover:bg-gray-100">
                            Logout
                        </button>
                    </div>
                @else
                    <a href="{{ route('loginPage') }}" class="text-gray-700 hover:text-blue-600" title="Login">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4M10 17l5-5-5-5M15 12H3" />
                        </svg>
                    </a>
                @endif
            </div>

        </div>
    </div>

    <!-- Mobile Search (Collapsible) -->
    <div x-show="mobileSearchOpen" x-transition x-cloak class="sm:hidden mt-3">

        <div class="relative">
            <input type="text" id="searchMobile" placeholder="Search restaurants or food..."
                class="w-full border border-gray-300 rounded px-3 py-2 pr-9
                       focus:outline-none focus:ring-1 focus:ring-gray-500" />

            <button type="button" id="clearSearchMobile"
                class="absolute right-2 top-1/2 -translate-y-1/2
                       text-gray-400 hover:text-gray-700 hidden">
                ✕
            </button>
        </div>

    </div>

</header>

<!-- Alpine -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<script type="module">
    import {
        httpRequest,
        showConfirmAlert
    } from '/js/httpClient.js';

    // Preserve search query
    const params = new URLSearchParams(window.location.search);
    const searchValue = params.get("search");
    const searchBar = document.getElementById('search');

    if (searchBar && searchValue) {
        searchBar.value = searchValue;
    }

    // Logout
    const logoutBtn = document.getElementById("btnLogout");
    if (logoutBtn) {
        logoutBtn.addEventListener("click", async () => {
            const result = await showConfirmAlert("question", "Sure want to logout?");
            if (!result.isConfirmed) return;

            await httpRequest('/api/auth/logout', {
                method: 'POST'
            });
            window.location.href = @json(route('homePage'));
        });
    }

    // Clearable Search
    function setupClearableSearch(inputId, clearBtnId) {
        const input = document.getElementById(inputId);
        const clearBtn = document.getElementById(clearBtnId);

        if (!input || !clearBtn) return;

        const toggleClear = () => {
            clearBtn.classList.toggle('hidden', !input.value);
        };

        input.addEventListener('input', toggleClear);

        clearBtn.addEventListener('click', () => {
            input.value = '';
            input.focus();
            toggleClear();
        });

        toggleClear();
    }

    setupClearableSearch('search', 'clearSearch');
    setupClearableSearch('searchMobile', 'clearSearchMobile');

    // Sync Desktop & Mobile
    const desktopSearch = document.getElementById('search');
    const mobileSearch = document.getElementById('searchMobile');

    if (desktopSearch && mobileSearch) {
        desktopSearch.addEventListener('input', () => {
            mobileSearch.value = desktopSearch.value;
        });

        mobileSearch.addEventListener('input', () => {
            desktopSearch.value = mobileSearch.value;
        });
    }
</script>
