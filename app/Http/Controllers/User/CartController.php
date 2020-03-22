<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\CartRequest;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Repositories\SellerRepository;
use JWTAuth;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CartRequest $request)
    {
        $user = JWTAuth::parseToken()->authenticate();

        $order_products_input = $request->only(['product_id', 'shop_id', 'price', 'count']);
        $order_input['user_id'] = $user->id;
        $order_input['status'] = 'waiting';
        $order_products_input['price'] = 5000;

        $order = Order::firstOrCreate($order_input);

        $order_products_input['order_id'] = $order->id;
        $order_products_input['price'] = $this->calculatePrice($request);

        OrderProduct::updateOrCreate([
            'order_id' => $order->id,
            'shop_id' => $request->shop_id,
            'product_id' => $request->product_id
            ], $order_products_input
        );

        return response()->json(['message'=> 'You have successfully updated your cart.']);
    }

    public function calculatePrice($request)
    {
        $shopRepository = new SellerRepository();

        $shop = $shopRepository->get($request->shop_id);
        $product = $shop->products()->whereIn('product_id', [$request->product_id])->firstOrfail();

        $raw_price = $product->pivot->price;
        $calculated_price = $request->count * $raw_price;

        return $calculated_price;
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
