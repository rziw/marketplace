<?php


namespace App\Repositories;


use App\Interfaces\Repository;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class OrderRepository implements Repository
{
    private $user;

    public function __construct()
    {
        try {
            $this->user = JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            return response()->json(['error' => 'Token is not valid'], 401);
        }
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

    public function getByUserId()
    {
        $order = Order::where('user_id', $this->user->id)
            ->with('orderproducts')
            ->where('status', 'waiting')
            ->firstOrFail();

        return $order;
    }

    public function getByProductId($product_id)
    {
        $order = Order::where('user_id', $this->user->id)->where('status', 'waiting')
            ->whereHas('orderproducts', function ($query) use ($product_id) {
                $query->whereId($product_id);
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

    public function getOrderWithProductsAndUsersByShopId($shop_id, $items_per_page)
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
