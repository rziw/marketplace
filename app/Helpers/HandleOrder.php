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

    public function permanentlyRemoveOrderProduct($order)
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
}
