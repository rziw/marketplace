<?php

namespace App\Services;

use App\Models\Order;
use App\Enums\OrderStatus;
use App\Models\OrderProduct;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\User\CartRequest;

class OrderCreationService
{
    private $orderInput;
    private $orderProductInput;
    private $shopDataProviderService;
    private $orderCalculationService;

    public function __construct(
        ShopDataProviderService $shopDataProviderService,
        OrderCalculationService $orderCalculationService
    )
    {
        $this->shopDataProviderService = $shopDataProviderService;
        $this->orderCalculationService = $orderCalculationService;
    }

    public function provideOrderInput(): void
    {
        $this->orderInput['user_id'] = auth('api')->user()->id;
        $this->orderInput['status'] = OrderStatus::waiting;
    }

    public function provideOrderProductInput(CartRequest $request, int $orderId): void
    {
        $this->orderProductInput = $request->only(['product_id', 'shop_id', 'count', 'product_name']);
        $this->orderProductInput['order_id'] = $orderId;
        $product = $this->shopDataProviderService->getShopProduct($request->shop_id, $request->product_id);
        $this->orderProductInput['price'] = $this->orderCalculationService->calculatePriceAction(
            $product->pivot->price,
            $request->count
        );
    }

    public function storeOrder(CartRequest $request): void
    {
        DB::transaction(function () use ($request) {
            $order = Order::firstOrCreate($this->orderInput);
            $this->provideOrderProductInput($request, $order->id);
            $this->storeOrderProductAction($request, $order->id);
        });
    }

    public function storeOrderProductAction(CartRequest $request, int $orderId): void
    {
        OrderProduct::updateOrCreate([
            'order_id' => $orderId,
            'shop_id' => $request->shop_id,
            'product_id' => $request->product_id
        ], $this->orderProductInput);
    }
}
