<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Repositories\OrderRepository;
use App\Services\OrderDeletionService;
use App\Services\OrderCreationService;
use App\Http\Requests\User\CartRequest;

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

    public function show(
        int $orderId,
        OrderRepository $orderRepository,
        OrderDeletionService $orderDeletionService
    ): JsonResponse
    {
        $order = $orderRepository->findByUser($orderId, auth('api')->user()->id);
        $deletingMessage = $orderDeletionService->permanentlyRemoveOrderProduct($order);

        return response()->json(compact('order', 'deletingMessage'));
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
