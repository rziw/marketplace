<?php

namespace App\Services\Payment;

use Illuminate\Http\Request;
use App\Repositories\OrderRepository;

class FailedPay extends Pay
{
    private $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function track(Request $request)
    {
        $this->storePayment($request);
        //Do some other stuff ...
    }

}
