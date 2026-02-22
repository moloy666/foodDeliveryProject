<div class="flex flex-col lg:flex-row flex-1 max-w-7xl mx-auto px-4 py-6 gap-6 pb-20 lg:pb-6">

    <!-- ========== Desktop Sidebar ========== -->
    <!-- ========== Desktop Sidebar ========== -->
    <aside
        class="hidden lg:flex lg:flex-col w-64 bg-white shadow-lg 
              min-h-[calc(100vh-4rem)] sticky top-16">

        <!-- Sidebar Header -->
        <div class="px-6 py-6 border-b">
            <h2 class="text-lg font-semibold text-gray-800">Account</h2>
            <p class="text-sm text-gray-500">Manage your profile</p>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-4 py-6 space-y-2 text-sm">

            <!-- Profile -->
            <a href="{{ route('profilePage') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-lg transition
            {{ request()->routeIs('profilePage') ? 'bg-gray-800 text-white shadow' : 'text-gray-700 hover:bg-gray-100' }}">

                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M5.121 17.804A9 9 0 1118.88 17.8M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Profile
            </a>

            <!-- Addresses -->
            <a href="{{ route('profilePage') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-lg transition
            {{ request()->routeIs('loginPage*') ? 'bg-gray-800 text-white shadow' : 'text-gray-700 hover:bg-gray-100' }}">

                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 21c4-4 6-6.5 6-9a6 6 0 10-12 0c0 2.5 2 5 6 9z" />
                </svg>
                Addresses
            </a>

            <!-- Orders -->
            <a href="{{ route('orderPage') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-lg transition
            {{ request()->routeIs('orderPage') ? 'bg-gray-800 text-white shadow' : 'text-gray-700 hover:bg-gray-100' }}">

                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 5h6m2 0a2 2 0 012 2v12l-4-2-4 2-4-2-4 2V7a2 2 0 012-2h2" />
                </svg>
                Orders
            </a>

            <!-- Chats -->
            <a href="{{ route('userChatList') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-lg transition
            {{ request()->routeIs('userChatList*') ? 'bg-gray-800 text-white shadow' : 'text-gray-700 hover:bg-gray-100' }}">

                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M8 10h8M8 14h5m9-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Chats
            </a>

            <!-- Seller Dashboard -->
            <a href="{{ route('sellerDashboardPage') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-lg transition
            {{ request()->routeIs('seller*') ? 'bg-gray-800 text-white shadow' : 'text-gray-700 hover:bg-gray-100' }}">

                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M5 7v13m14-13v13M8 21h8" />
                </svg>
                Seller Dashboard
            </a>

        </nav>

        <!-- Bottom Section (Optional) -->
        <div class="px-6 py-4 border-t text-xs text-gray-400">
            FulBite v1.0
        </div>

    </aside>


    <!-- ========== Main Content ========== -->
    <main class="flex-1 bg-white rounded shadow p-4 sm:p-6 min-h-[400px]">
        @yield('content')
    </main>

</div>



<!-- ========== Mobile Bottom Navigation ========== -->
<nav class="lg:hidden fixed bottom-0 left-0 right-0 bg-white border-t shadow z-50">
    <div class="flex justify-around items-center text-xs">

        <!-- Profile -->
        <a href="{{ route('profilePage') }}"
            class="flex flex-col items-center py-2 w-full {{ request()->routeIs('profilePage') ? 'text-gray-900 font-semibold' : 'text-gray-500' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mb-1" fill="none" stroke="currentColor"
                stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M5.121 17.804A9 9 0 1118.88 17.8M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            Profile
        </a>

        <!-- Addresses -->
        <a href="{{ route('profilePage') }}"
            class="flex flex-col items-center py-2 w-full {{ request()->routeIs('loginPage*') ? 'text-gray-900 font-semibold' : 'text-gray-500' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mb-1" fill="none" stroke="currentColor"
                stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 21c4-4 6-6.5 6-9a6 6 0 10-12 0c0 2.5 2 5 6 9z" />
            </svg>
            Address
        </a>

        <!-- Orders -->
        <a href="{{ route('orderPage') }}"
            class="flex flex-col items-center py-2 w-full {{ request()->routeIs('orderPage') ? 'text-gray-900 font-semibold' : 'text-gray-500' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mb-1" fill="none" stroke="currentColor"
                stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 5h6m2 0a2 2 0 012 2v12l-4-2-4 2-4-2-4 2V7a2 2 0 012-2h2" />
            </svg>
            Orders
        </a>

        <!-- Chats -->
        <a href="{{ route('userChatList') }}"
            class="flex flex-col items-center py-2 w-full {{ request()->routeIs('userChatList*') ? 'text-gray-900 font-semibold' : 'text-gray-500' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mb-1" fill="none" stroke="currentColor"
                stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M8 10h8M8 14h5m9-2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Chats
        </a>

        <!-- Seller -->
        <a href="{{ route('sellerDashboardPage') }}"
            class="flex flex-col items-center py-2 w-full {{ request()->routeIs('seller*') ? 'text-gray-900 font-semibold' : 'text-gray-500' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mb-1" fill="none" stroke="currentColor"
                stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M5 7v13m14-13v13M8 21h8" />
            </svg>
            Seller
        </a>

    </div>
</nav>
