@extends('layouts.app')

@section('title', 'Home Page')

@section('content')

    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="/" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-gray-600">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z">
                        </path>
                    </svg>
                    Home
                </a>
            </li>

            <li>
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Restaurants</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 p-4" id="restaurantList">

        @for ($i = 0; $i < 6; $i++)
            <div class="animate-pulse bg-white rounded-lg shadow-md overflow-hidden flex flex-col">
                <div class="h-40 bg-gray-300 w-full"></div>
                <div class="p-4 flex flex-col flex-grow space-y-3">
                    <div class="h-6 bg-gray-300 rounded w-3/4"></div>
                    <div class="h-4 bg-gray-300 rounded w-full"></div>
                    <div class="h-4 bg-gray-300 rounded w-5/6"></div>
                    <div class="h-8 bg-gray-300 rounded w-24 mt-auto"></div>
                </div>
            </div>
        @endfor

    </div>

    <script type="module">
        import {
            httpRequest
        } from '/js/httpClient.js';


        async function restaurants() {
            try {
                const url = `/api/restaurants`;
                const res = await httpRequest(url);
                const restaurants = res?.data?.restaurants || [];

                let html = "";

                restaurants.forEach((item) => {
                    html += `
                    
                    <a href="/restaurants/${item.uid}">
                        <div class="bg-white rounded-lg shadow-md overflow-hidden flex flex-col">
                            <img src="${item.images?.front_image }" alt="${item.name}"
                                class="h-40 w-full object-cover" />

                            <div class="p-4 flex flex-col flex-grow">
                                <h3 class="text-lg font-semibold mb-1">
                                    ${item.name}
                                </h3>
                                <p class="text-gray-500 text-sm mb-3">${item.description}</p>
                                <p class="text-gray-500 text-sm mb-3">
                                    ${item.addresses?.address_line_1}, ${item.addresses?.city}, ${item.addresses?.postal_code}
                                </p>
                            </div>
                        </div>
                        
                    </a>
                   
                    `
                });

                document.getElementById('restaurantList').innerHTML = html

            } catch (err) {
                console.log("Error :", err.message);
            }
        }

        restaurants();

        async function fetchCartItems() {
            try {
                const res = await httpRequest(`/api/cart-items`);
                const items = res?.data?.items || [];
                document.getElementById("cartCount").innerText = items.length;

            } catch (error) {
                console.log(error);
            }
        }

        fetchCartItems();
    </script>

@endsection
