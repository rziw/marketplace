<?php

namespace App\Http\Middleware;

use App\Repositories\OrderRepository;
use Closure;

class CheckProductCount
{
    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $order = isset($request->order) ? $request->order : $this->orderRepository->getByUserId();
        $not_enough_quantity_message = $this->checkOrderedProductsAvailability($order);

        if(count($not_enough_quantity_message) > 0) {
            return response()->json(['message'=> $not_enough_quantity_message]);
        }

        return $next($request);
    }

    private function checkOrderedProductsAvailability($order)
    {
        $ordered_products_result = $this->orderRepository->getOrderedProducts($order->id);
        $not_enough_quantity_message = array();

        foreach ($ordered_products_result as $ordered_product) {
            if ($ordered_product->order_products_count > $ordered_product->product_count) {
                $not_enough_quantity_message[] = "quantity of this item : $ordered_product->product_name is changed
                 and just $ordered_product->product_count of it is available";
            }
        }

        return $not_enough_quantity_message;
    }
}
