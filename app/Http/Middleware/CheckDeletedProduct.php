<?php

namespace App\Http\Middleware;

use App\Helpers\OrderHandler;
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
    public function __construct(OrderHandler $orderHandler, OrderRepository $orderRepository)
    {
        $this->handleOrder = $orderHandler;
        $this->orderRepository = $orderRepository;
    }

    public function handle($request, Closure $next)
    {
        $order = $this->orderRepository->get($request->order_id);
        $request->order = $order;

        $removed_product_message = $this->handleOrder->permanentlyRemoveOrderProduct($order);

        if(count($removed_product_message) > 0) {
            return response()->json(['message'=> $removed_product_message]);
        }

        $available_products = $order->orderproducts()->whereNull('status')->get();

        if($available_products->count() < 1) {
            return response()->json(['error'=> 'the cart is empty']);
        }

        return $next($request);
    }
}
