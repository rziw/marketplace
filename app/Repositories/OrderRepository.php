<?php

namespace App\Repositories;

use App\Models\Order;
use App\Enums\OrderStatus;
use App\Interfaces\Repository;
use Illuminate\Support\Facades\DB;

class OrderRepository implements Repository
{
    public function findByUser(int $orderId, int $userId)
    {
        $order = Order::where('id', $orderId)
            ->where('user_id', $userId)
            ->with('orderproducts')
            ->where('status', OrderStatus::waiting)
            ->firstOrFail();

        return $order;
    }

    public function getByUserId()
    {
        $order = Order::where('user_id', $this->user->id)
            ->with('orderproducts')
            ->where('status', 'waiting')
            ->firstOrFail();

        return $order;
    }

    public function getByProductId(int $productId, int $userId)
    {
        $order = Order::where('user_id', $userId)->where('status', OrderStatus::waiting)
            ->whereHas('orderProducts', function ($query) use ($productId) {
                $query->where('product_id', $productId);
            })->firstOrFail();

        return $order;
    }

    public function getOrderedProducts($order_id)
    {
        $product_result = DB::table('order_products')->where('order_products.order_id', $order_id)
            ->join('shops', 'order_products.shop_id', '=', 'shops.id')
            ->join('product_shop', 'order_products.product_id', '=', 'product_shop.product_id')
            ->select(
                'order_products.id AS order_products_id',
                'order_products.product_id AS order_products_product_id',
                'order_products.count AS order_products_count',
                'order_products.product_name AS product_name',
                'product_shop.count AS product_count',
                'product_shop.id AS product_id',
                'shops.id AS shop_id'
            )->get();

        return $product_result;
    }

    public function listWithProductsAndUsersByShopId($shop_id, $items_per_page)
    {
        $orders_products = Order::with('orderproducts')
            ->whereHas('orderproducts', function ($query) use ($shop_id) {
                $query->where('shop_id', $shop_id);
            })->with('user')->paginate($items_per_page);

        return $orders_products;
    }

    public function list()
    {
        // TODO: Implement list() method.
    }

    public function listByProductId($product_id)
    {
        $orders = Order::where('status', 'waiting')
            ->whereHas('orderproducts', function ($q) use ($product_id) {
                $q->where('order_products.product_id', $product_id);
            })->get();

        return $orders;
    }
}
