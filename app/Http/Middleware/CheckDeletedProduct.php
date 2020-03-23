<?php

namespace App\Http\Middleware;

use App\Helpers\HandleOrder;
use App\Repositories\OrderRepository;
use Closure;

class CheckDeletedProduct
{
    private $handleOrder;
    private $orderRepository;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function __construct()
    {
        $this->handleOrder = new HandleOrder();
        $this->orderRepository = new OrderRepository();
    }

    public function handle($request, Closure $next)
    {
        $order = $this->orderRepository->get($request->order_id);

        $message = $this->handleOrder->permanentlyRemoveOrderProduct($order);

        if(count($message) > 0) {
            return response()->json(['message'=> $message]);
        }

        $available_products = $order->orderproducts()->whereNull('status')->get();

        if($available_products->count() < 1) {
            return response()->json(['error'=> 'the cart is empty']);
        }

        return $next($request);
    }
}
