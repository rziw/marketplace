<?php

namespace App\Http\Controllers\User;

use App\Helpers\OrderHandler;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\CartRequest;
use App\Repositories\OrderRepository;
use App\Services\OrderCreationService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('check.cart.product.count', ['only' => ['store']]);
    }

    public function store(CartRequest $request, OrderCreationService $orderCreationService): JsonResponse
    {
        $orderCreationService->provideOrderInput();
        $orderCreationService->storeOrder($request);

        return response()->json(['message' => 'You have successfully updated your cart.']);
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
