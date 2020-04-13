<?php

namespace App\Http\Controllers\User;

use App\Helpers\OrderHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\CartRequest;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Repositories\OrderRepository;
use App\Repositories\SellerRepository;
use JWTAuth;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('product.count', ['only' => ['store']]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CartRequest $request)
    {
        $order_products_input = $request->only(['product_id', 'shop_id', 'count', 'product_name']);
        $order_input['user_id'] = $request->user->id;
        $order_input['status'] = 'waiting';

        $order = Order::firstOrCreate($order_input);

        $order_products_input['order_id'] = $order->id;
        $order_products_input['price'] = $this->calculatePrice($request);

        OrderProduct::updateOrCreate([
            'order_id' => $order->id,
            'shop_id' => $request->shop_id,
            'product_id' => $request->product_id
        ], $order_products_input
        );

        return response()->json(['message' => 'You have successfully updated your cart.']);
    }

    public function calculatePrice($request)
    {
        $shopRepository = new SellerRepository();

        $shop = $shopRepository->get($request->shop_id);
        $product = $shop->products()->whereIn('product_id', [$request->product_id])->firstOrfail();

        $raw_price = $product->pivot->price;
        $calculated_price = $request->count * $raw_price;

        return $calculated_price;
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id, OrderHandler $handleOrder, OrderRepository $orderRepository, Request $request)
    {
        return $request->user;
        $cart = $orderRepository->get($id);

        $message = $handleOrder->permanentlyRemoveOrderProduct($cart);
        $available_products = $cart->orderproducts()->whereNull('status')->get();

        if ($available_products->count() < 1) {
            return response()->json([
                'error' => 'the cart is empty',
                'message' => $message
            ]);
        }

        return response()->json(compact('cart', 'message'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id, OrderRepository $orderRepository)
    {
        $cart = $orderRepository->getWithProductId($id);

        if ($cart->orderproducts()->count() == 1) {
            $cart->delete();
            return response()->json(['message' => 'You have successfully deleted the product. cart is empty now']);
        }

        $cart->orderproducts()->whereId($id)->delete();

        return response()->json(['message' => 'You have successfully deleted the product']);
    }
}
