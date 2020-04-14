<?php


namespace App\Helpers;


use App\Models\Order;
use App\Repositories\SellerRepository;

class OrderHandler
{
    public function updateOrderStatus($order, $status)
    {
        $order->update(['status' => $status]);
    }

    public function calculateAnOrderedProductPrice($request)
    {
        $shopRepository = new SellerRepository();

        $shop = $shopRepository->get($request->shop_id);
        $product = $shop->products()->whereIn('product_id', [$request->product_id])->firstOrfail();

        $raw_price = $product->pivot->price;
        $calculated_price = $request->count * $raw_price;

        return $calculated_price;
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
