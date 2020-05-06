<?php


namespace App\Repositories;


use App\Interfaces\Repository;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductRepository implements Repository
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

    public function getClosest($id, $lat, $lng, $radius)
    {
        $product = Product::where('id', $id)->whereHas('shops', function($q) use ($lat, $lng, $radius) {
            $q->whereRaw('6367 * acos( cos( radians(' . $lat . ') ) * cos( radians( latitude ) ) *
             cos( radians( longitude ) - radians(' . $lng . ') ) + sin( radians(' . $lat . ') ) *
              sin( radians( latitude ) ) ) < ' . $radius . '');
            $q->where('product_shop.status', 'accepted');
            $q->where('shops.status', 'accepted');
        })->with('shops')
            ->get();

        return $product;
    }

    public function get($id)
    {
        $product = Product::where('id', $id)->whereHas('shops', function($q) {
            $q->where('product_shop.status', 'accepted');
            $q->where('shops.status', 'accepted');
        })->with('shops')
            ->get();

        return $product;
    }

    public function getFromSpecificShop($shop, $id)
    {
        return $shop->products()->where('products.id', $id)->firstOrFail();
    }
}
