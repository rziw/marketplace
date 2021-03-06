<?php

namespace App\Http\Controllers\User;

use App\Helpers\OrderHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\CartRequest;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Repositories\OrderRepository;
use JWTAuth;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;//it's beet set in jwt middleware on top of user.php route
        $this->middleware('check.cart.product.count', ['only' => ['store']]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CartRequest $request, OrderHandler $orderHandler)
    {
        $order_input['user_id'] = $this->request->user->id;
        $order_input['status'] = 'waiting';

        $order = Order::firstOrCreate($order_input);
        $this->storeOrderProduct($request, $order->id, $orderHandler);

        return response()->json(['message' => 'You have successfully updated your cart.']);
    }

    private function storeOrderProduct($request, $order_id, $orderHandler)
    {
        $order_products_input = $request->only(['product_id', 'shop_id', 'count', 'product_name']);
        $order_products_input['order_id'] = $order_id;
        $order_products_input['price'] = $orderHandler->calculateAnOrderedProductPrice($request);

        OrderProduct::updateOrCreate([
            'order_id' => $order_id,
            'shop_id' => $request->shop_id,
            'product_id' => $request->product_id
        ], $order_products_input);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id, OrderHandler $handleOrder, OrderRepository $orderRepository, Request $request)
    {
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
        $cart = $orderRepository->getByProductId($id);

        if ($cart->orderproducts()->count() == 1) {
            $cart->delete();
            return response()->json(['message' => 'You have successfully deleted the product. cart is empty now']);
        }

        $cart->orderproducts()->whereId($id)->delete();

        return response()->json(['message' => 'You have successfully deleted the product']);
    }
}
