<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Shop;
use App\Repositories\OrderRepository;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Shop $shop
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Shop $shop, OrderRepository $orderRepository)
    {
        $items_per_page = request()->has('per_page') ? request('per_page') : 10;
        $orders = $orderRepository->getOrderWithProductsAndUsersByShopId($shop->id, $items_per_page);

        return response()->json(compact('orders'));
    }

}
