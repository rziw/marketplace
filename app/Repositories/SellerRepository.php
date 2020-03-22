<?php


namespace App\Repositories;


use App\Models\Shop;

class SellerRepository
{
    public function getClosest($id, $lat, $lng, $radius)
    {
        $shop = Shop::where('id', $id)
            ->where('status', 'accepted')
            ->whereRaw('6367 * acos( cos( radians(' . $lat . ') ) * cos( radians( latitude ) ) *
             cos( radians( longitude ) - radians(' . $lng . ') ) + sin( radians(' . $lat . ') ) *
              sin( radians( latitude ) ) ) < ' . $radius . '')
            ->with(['products' => function ($query) {
                $query->where('product_shop.status', 'accepted');
            }])->firstOrFail();

        return $shop;
    }

    public function get($id)
    {
        $shop = Shop::where('id', $id)
            ->where('status', 'accepted')
            ->with(['products' => function ($query) {
                $query->where('product_shop.status', 'accepted');
            }])->firstOrFail();

        return $shop;
    }
}
