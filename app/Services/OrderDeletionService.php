<?php

namespace App\Services;

use App\Models\Order;
use App\Repositories\OrderRepository;

class OrderDeletionService
{
    private $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function permanentlyRemoveOrderProduct(Order $order)
    {
        $deleted_products = $order->orderproducts()->where('status', 'deleted')->get();

        $message = array();

        foreach ($deleted_products as $ordered_product) {
            $message[] = "this product was deleted from your cart because of lack of quantity : "
                .$ordered_product->product_name;
            $ordered_product->delete();//permanently delete
        }

        return $message;
    }

    public function destroyOrderProduct(int $productId)
    {
        $order = $this->orderRepository->getByProductId($productId, auth('api')->user()->id);

        if ($order->orderproducts()->count() == 1) {
            $order->delete();
        }

        $order->orderproducts()->whereId($productId)->delete();
    }
}
