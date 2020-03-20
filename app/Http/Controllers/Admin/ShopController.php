<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateShopRequest;
use App\Models\Shop;
use App\Services\GeoLocationHandler;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $shops = Shop::get();
        return response()->json(compact('shops'));
    }

    /**
     * Display the specified resource.
     *
     * @param  Shop  $shop
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Shop $shop)
    {
        return response()->json(compact('shop'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Shop  $shop
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateShopRequest $request, Shop $shop)
    {
        $geoLocation = new GeoLocationHandler($shop, $request);

        $input = $request->only(['name', 'sheba_number', 'product_type', 'address', 'province', 'city']);
        $input['longitude'] = $geoLocation->getLongitude();
        $input['latitude'] = $geoLocation->getLatitude();

        $shop->update($input);

        return response()->json(['message'=> 'You have successfully updated the shop.']);
    }
}
