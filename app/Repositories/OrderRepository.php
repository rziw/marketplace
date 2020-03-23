<?php


namespace App\Repositories;


use App\Interfaces\Repository;
use App\Models\Order;
use JWTAuth;

class OrderRepository implements Repository
{
    private $user;

    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function get($id)
    {
        $order = Order::where('id', $id)
            ->where('user_id', $this->user->id)
            ->with('orderproducts')
            ->where('status', 'waiting')
            ->firstOrFail();

        return $order;
    }

    public function list()
    {
        // TODO: Implement list() method.
    }

    public function listWithProductId($product_id)
    {
        $orders = Order::where('status', 'waiting')
            ->whereHas('orderproducts', function ($q) use($product_id) {
                $q->where('order_products.product_id', $product_id);
            })->get();

        return $orders;
    }
}
