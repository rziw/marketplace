<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Requests\Seller\UpdateShopRequest;
use App\Models\Shop;

class ShopController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  Shop $shop
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Shop $shop)
    {
        return response()->json(compact('shop'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Shop $shop
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateShopRequest $request, Shop $shop)
    {
        $input = $request->only(['name', 'sheba_number', 'product_type', 'address']);
        $shop->update($input);

        return response()->json(['message'=> 'You have successfully updated the shop.']);
    }
}
