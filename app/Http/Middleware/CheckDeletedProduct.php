<?php

namespace App\Http\Middleware;

use Closure;
use App\Repositories\OrderRepository;
use App\Services\OrderDeletionService;

class CheckDeletedProduct
{
    private $orderDeletionService;
    private $orderRepository;

    public function __construct(OrderDeletionService $orderDeletionService, OrderRepository $orderRepository)
    {
        $this->orderDeletionService = $orderDeletionService;
        $this->orderRepository = $orderRepository;
    }

    public function handle($request, Closure $next)
    {
        $order = $this->orderRepository->findByUser($request->order_id, auth('api')->user()->id);
        $request->order = $order;

        $removed_product_message = $this->orderDeletionService->permanentlyRemoveOrderProduct($order);

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
