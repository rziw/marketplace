<?php


namespace App\Repositories;


use App\Interfaces\Repository;
use App\Models\Shop;

class ShopRepository implements Repository
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

    public function getWithoutRelation($id)
    {
        return Shop::findOrFail($id);
    }

    public function list()
    {
        // TODO: Implement list() method.
    }
}
