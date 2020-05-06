<?php


namespace App\Helpers;


class OrderHandler
{
    public function updateOrderStatus($order, $status)
    {
        $order->update(['status' => $status]);
    }

    public function calculateAnOrderedProductPrice($request)
    {
        $shop = app()->call('App\Repositories\ShopRepository@get', [$request->shop_id]);
        $product = $shop->products()->whereIn('product_id', [$request->product_id])->firstOrfail();

        return $this->calculatePriceAction($product->pivot->price, $request->count);
    }

    private function calculatePriceAction($productPrice, $requestedCount)
    {
        return $requestedCount * $productPrice;
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
