<?php


namespace App\Repositories;


use App\Models\Product;
use App\Models\Shop;
use Illuminate\Support\Facades\DB;

class ProductRepository
{
    public function listClosest($lat, $lng, $radius)
    {
        $shops = Shop::select(DB::raw('id,  ( 6367 * acos( cos( radians('.$lat.') ) * cos( radians( latitude ) ) *
         cos( radians( longitude ) - radians('.$lng.') ) + sin( radians('.$lat.') ) * sin( radians( latitude ) ) ) )
         AS distance'))
            ->where('status', 'accepted')
            ->having('distance', '<', $radius)
            ->get();

        $products = Product::whereHas('shops', function($q) use($shops) {
                $q->where('product_shop.status', 'accepted');
                $q->whereIn('shops.id', $shops->pluck('id')->all());
            })
            ->with('shops')
            ->get();

        return $products;
    }

    public function list()
    {
        $products = Product::select(DB::raw('*'))
            ->whereHas('shops', function($q) {
                $q->where('product_shop.status', 'accepted');
            }) ->with('shops')
            ->get();

        return $products;
    }
}
