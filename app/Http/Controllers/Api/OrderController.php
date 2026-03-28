<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;

use App\Models\Order;
use App\Models\OrderItems;

class OrderController extends Controller
{
    use ApiResponse;

    public function getOrders(Request $request)
    {
        try {
            $user_uid = $request->user()->uid;

            $orders = Order::with([
                'restaurant:uid,name,phone',

                'order_items' => function ($q) {
                    $q->select('uid', 'order_uid', 'food_uid', 'quantity', 'price', 'total');
                },

                'order_items.food' => function ($q) {
                    $q->select('uid', 'name', 'price');
                }
            ])
                ->where('user_uid', $user_uid)
                ->orderBy('id', 'DESC')
                ->get();

            return $this->successResponse(200, 'Orders', [
                'orders' => $orders,
            ]);
        } catch (\Throwable $th) {
            return $this->errorResponse(500, 'Orders', [$th->getMessage()]);
        }
    }

    public function getRestaurantOrders(Request $request, $restaurantId)
    {
        try {
            $orders = Order::with([
                'user:uid,name,email',

                'order_items' => function ($q) {
                    $q->select('uid', 'order_uid', 'food_uid', 'quantity', 'price', 'total');
                },

                'order_items.food' => function ($q) {
                    $q->select('uid', 'name', 'price');
                }
            ])
                ->where('restaurant_uid', $restaurantId)
                ->orderBy('id', 'DESC')
                ->get();

            return $this->successResponse(200, 'Orders', [
                'orders' => $orders,
            ]);
        } catch (\Throwable $th) {
            return $this->errorResponse(500, 'Orders', [$th->getMessage()]);
        }
    }
}
