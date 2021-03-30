<?php

namespace App\Http\Controllers\User;

use App\Services\Order\CheckProductCountForAddingToCartGuard;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Repositories\OrderRepository;
use App\Services\OrderDeletionService;
use App\Services\OrderCreationService;
use App\Http\Requests\User\CartRequest;

class CartController extends Controller
{
    public function store(
        CartRequest $request,
        OrderCreationService $orderCreationService,
        CheckProductCountForAddingToCartGuard $guard
    ): JsonResponse
    {
        $guard->handle($request);
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
