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

    public function destroy(int $productId, OrderDeletionService $orderDeletionService): JsonResponse
    {
        $orderDeletionService->destroyOrderProduct($productId);

        return response()->json(['message' => 'You have successfully deleted the product']);
    }
}
