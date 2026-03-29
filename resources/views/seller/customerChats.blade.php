@extends('layouts.seller')

@section('title', 'Seller Dashboard')

@section('content')

    <div class="space-y-5 max-w-xl">
        
    </div>

    <script type="module">
        import {
            httpRequest
        } from '/js/httpClient.js';


        async function getCustomers() {
            try {

                const url = `/api/restaurants/{{ $restaurantId }}/basic-details`
                const res = await httpRequest(url);

            } catch (err) {
                console.log("Error :", err.message);
            }
        }

        getCustomers();
    </script>

@endsection
