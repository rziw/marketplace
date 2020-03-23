<?php

namespace App\Listeners;

use App\Events\ProductFinished;
use App\Repositories\OrderRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateCarts implements ShouldQueue
{
    private $orderRepository;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->orderRepository = new OrderRepository();
    }

    /**
     * Handle the event.
     *
     * @param ProductFinished $event
     * @return void
     */
    public function handle(ProductFinished $event)
    {
        $orders = $this->orderRepository->listWithProductId($event->product->id);

        foreach ($orders as $order) {
            $order->orderproducts()->where('product_id', $event->product->id)->update(['status' => 'deleted']);
        }
    }
}
