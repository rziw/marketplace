<?php


namespace App\Helpers;


use App\Events\ProductFinished;
use App\Models\Shop;

class ProductQuantityChanging
{
    public function updateQuantity($order)
    {
        foreach ($order->orderproducts as $orderproduct) {
            $shop = Shop::where('id', $orderproduct->shop_id)->first();
            $product = $shop->products->where('id', $orderproduct->product_id)->first();

            $product->pivot->count = $product->pivot->count - $orderproduct->count;
            $product->pivot->save();

            if($product->pivot->count == 0) {
                event(new ProductFinished($product));
            }
        }
    }
}
