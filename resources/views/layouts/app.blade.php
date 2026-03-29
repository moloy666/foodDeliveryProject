<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Default Title')</title>

    @include('layouts.headerLink')

</head>

<body>

    @php
        $isCartPage = request()->routeIs('cartPage');
        $isHomePage = request()->routeIs('homePage');
    @endphp

    @if (!$isCartPage)
        @include('layouts.header')
    @endif


    <div class="container min-h-[calc(100vh-6rem)] mx-auto px-4 py-8">
        @yield('content')

        @if (!$isCartPage)
            <a id="floatingCart" href="{{ route('cartPage') }}" class="fixed-btn floating-cart-btn">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8"
                    stroke="currentColor" class="cart-icon">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437m0 0L6.75 14.25m-1.644-8.978h12.276c.938 0 1.636.88 1.42 1.792l-1.2 5.013a1.5 1.5 0 01-1.46 1.17H7.012m0 0L6.75 17.25m.262-2.988H18m-11.25 0A1.5 1.5 0 105.25 18a1.5 1.5 0 001.5-1.5zm10.5 0A1.5 1.5 0 1015.75 18a1.5 1.5 0 001.5-1.5z" />
                </svg>
                <span id="cartCount" class="cart-badge">0</span>
            </a>
        @endif

    </div>

    @if ($isHomePage)
        @include('layouts.footer')
    @endif

    @stack('scripts')

</body>

</html>


<style>
    .fixed-btn {
        position: fixed;
        right: 20px;
        z-index: 9999;
    }

    /* ── Floating Cart Button ── */
    .floating-cart-btn {
        bottom: 155px;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 52px;
        height: 52px;
        background: #111827;
        border-radius: 14px;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.22);
        text-decoration: none;
        transition: transform 0.15s ease, box-shadow 0.15s ease, background 0.15s ease;
        position: fixed;
    }

    .floating-cart-btn:hover {
        background: #1f2937;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.28);
    }

    .floating-cart-btn:active {
        transform: translateY(0);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }

    .cart-icon {
        width: 22px;
        height: 22px;
        color: #ffffff;
        flex-shrink: 0;
    }

    /* ── Cart Badge ── */
    .cart-badge {
        position: absolute;
        top: -7px;
        right: -7px;
        background: #ef4444;
        color: #fff;
        font-size: 10px;
        font-weight: 700;
        min-width: 18px;
        height: 18px;
        padding: 0 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 999px;
        border: 2px solid #fff;
        box-shadow: 0 1px 4px rgba(239, 68, 68, 0.4);
        line-height: 1;
    }

    /* Hide badge when 0 */
    .cart-badge:not(:empty)[data-count="0"],
    .cart-badge[data-zero="true"] {
        display: none;
    }
</style>
