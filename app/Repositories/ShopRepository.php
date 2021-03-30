<?php

namespace App\Repositories;

use App\Models\Shop;
use App\Interfaces\Repository;

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

    public function findByStatus(int $shopId, int $statusId)
    {
        $shop = Shop::where('id', $shopId)
            ->where('status', $statusId)
            ->with(['products' => function ($query) {
                $query->where('product_shop.status', 'accepted');
            }])->firstOrFail();

        return $shop;
    }

    public function find(int $shopId)
    {
        return Shop::findOrFail($shopId);
    }

    public function list()
    {
        // TODO: Implement list() method.
    }

    public function findByUser(int $orderId, int $userId)
    {
        // TODO: Implement findByUser() method.
    }
}
