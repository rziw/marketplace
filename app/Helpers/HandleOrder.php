<?php


namespace App\Helpers;


use App\Models\Order;

class HandleOrder
{
    public function updateOrderStatus($order, $status)
    {
        $order->update(['status' => $status]);
    }

    public function calculateOrderPrice(Order $order)
    {
        return $order->orderProducts->sum('price');
    }
}
