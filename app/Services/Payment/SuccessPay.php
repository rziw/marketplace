<?php

namespace App\Services\Payment;

use App\Models\Shop;
use App\Enums\OrderStatus;
use Illuminate\Http\Request;
use App\Events\ProductFinished;
use Illuminate\Support\Facades\DB;
use App\Repositories\OrderRepository;

class SuccessPay extends Pay
{
    private $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function track(Request $request)
    {
        DB::transaction(function () use ($request) {
            $this->storePayment($request);
            $this->orderRepository->update($request->order, ['status' => OrderStatus::paid]);
            $this->updateProductQuantity($request->order);
        });
    }

    public function updateProductQuantity($order)
    {
        foreach ($order->orderproducts as $orderproduct) {
            $shop = Shop::where('id', $orderproduct->shop_id)->first();
            $product = $shop->products->where('id', $orderproduct->product_id)->first();

            $product->pivot->count = $product->pivot->count - $orderproduct->count;
            $product->pivot->save();

            if($product->pivot->count == 0) {
                event(new ProductFinished($product));
            }
        }
    }
}
