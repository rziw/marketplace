<?php


namespace App\Repositories;


use App\Interfaces\repository;
use App\Models\Order;
use JWTAuth;

class OrderRepository implements repository
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
            ->firstOrFail();

        return $order;
    }

    public function list()
    {
        // TODO: Implement list() method.
    }
}
