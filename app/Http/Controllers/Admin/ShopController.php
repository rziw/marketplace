<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateShopRequest;
use App\Models\Shop;
use App\Notifications\MarketChange;
use App\Services\GeoLocationHandler;
use Illuminate\Support\Facades\Mail;

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
        $input = $request->only(['name', 'sheba_number', 'product_type', 'address', 'province', 'city', 'status']);

        if(isset($request->address)) {
            $geoLocation = new GeoLocationHandler($shop, $request);

            $input['longitude'] = $geoLocation->getLongitude();
            $input['latitude'] = $geoLocation->getLatitude();
        }

        $this->checkForNotifyUser($shop, $request);
        $shop->update($input);

        return response()->json(['message'=> 'You have successfully updated the shop.']);
    }

    private function checkForNotifyUser(Shop $shop, $request)
    {
        if(isset($request->status) && $request->status == 'accepted' && $shop->status != 'accepted') {
            Mail::fake();//Mock sending email unless all requirements of sending email are OK
            $message = 'status of shop updated to accepted.';
            $shop->user->notify(new MarketChange($message));
        }
    }
}
