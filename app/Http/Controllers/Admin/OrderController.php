<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Shop $shop
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Shop $shop)
    {
        $orders = $shop->orderProducts;
        return response()->json(compact('orders'));
    }

}
