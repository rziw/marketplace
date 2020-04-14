<?php

namespace App\Http\Middleware;

use App\Models\Shop;
use Closure;

class CheckProductCountForAddingToCart
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $not_enough_quantity_message = $this->checkProductCount($request);

        if(count($not_enough_quantity_message) > 0) {
            return response()->json(['message'=> $not_enough_quantity_message]);
        }

        return $next($request);
    }

    private function checkProductCount($request)
    {
        $shop = Shop::find($request->shop_id);
        $product_with_pivot = $shop->products()->where('products.id', $request->product_id)->firstOrFail();
        $not_enough_quantity_message = array();

        if ($product_with_pivot->pivot->count < $request->count) {
            $not_enough_quantity_message[] = "only ".$product_with_pivot->pivot->count." of this item is available";
        }

        return $not_enough_quantity_message;
    }
}
