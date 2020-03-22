<?php


namespace App\Repositories;


use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductRepository
{
    public function listClosest($lat, $lng, $radius)
    {
        $products = Product::whereHas('shops', function ($q) use ($lat, $lng, $radius) {
            $q->where('product_shop.status', 'accepted');
            $q->whereRaw('6367 * acos( cos( radians(' . $lat . ') ) * cos( radians( latitude ) ) *
             cos( radians( longitude ) - radians(' . $lng . ') ) + sin( radians(' . $lat . ') ) *
              sin( radians( latitude ) ) ) < ' . $radius . '');
            $q->where('shops.status', 'accepted');
        })
            ->with('shops')
            ->get();

        return $products;
    }

    public function list()
    {
        $products = Product::select(DB::raw('*'))
            ->whereHas('shops', function ($q) {
                $q->where('product_shop.status', 'accepted');
                $q->where('shops.status', 'accepted');
            })->with('shops')
            ->get();

        return $products;
    }
}