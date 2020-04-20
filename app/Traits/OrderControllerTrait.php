<?php


namespace App\Traits;


use App\Helpers\OrderStatusChanger;
use App\Http\Requests\Admin\UpdateOrderStatusRequest;
use App\Models\Order;
use App\Models\Shop;
use App\Repositories\OrderRepository;

trait OrderControllerTrait
{
    /**
     * Display a listing of the resource.
     *
     * @param Shop $shop
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Shop $shop, OrderRepository $orderRepository)
    {
        $items_per_page = request()->has('per_page') ? request('per_page') : 10;
        $orders = $orderRepository->listWithProductsAndUsersByShopId($shop->id, $items_per_page);

        return response()->json(compact('orders'));
    }

    public function updateStatus(Shop $shop, Order $order, UpdateOrderStatusRequest $request)
    {
        $orderStatusChanger = new OrderStatusChanger($request->status, $order);
        $update_result_message = $orderStatusChanger->checkAndUpdateStatus();

        return response()->json(['message'=> $update_result_message]);
    }
}
