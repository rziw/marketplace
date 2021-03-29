<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Services\GatewayFactory;
use App\Services\Payment\FailedPay;
use App\Http\Controllers\Controller;
use App\Services\Payment\SuccessPay;
use App\Repositories\OrderRepository;
use App\Http\Requests\User\PaymentRequest;

class PaymentController extends Controller
{
    private $orderRepository;
    private $gatewayFactory;

    public function __construct(orderRepository $orderRepository, GatewayFactory $gatewayFactory)
    {
        $this->orderRepository = $orderRepository;
        $this->gatewayFactory = $gatewayFactory;
    }

    public function pay(PaymentRequest $request)
    {
        $order = $this->orderRepository->findByUser($request->order_id, auth()->user()->id);
        $payment = $this->gatewayFactory->build($request->gateway);
        $request->amount = $order->total_price;

        $payment->pay($request);
    }

    public function trackSuccessfulPay(Request $request, SuccessPay $successPay)
    {
        $successPay->track($request);

        return response()->json(['message' => 'you successfully paid the order']);
    }

    public function trackFailedPay(Request $request, FailedPay $failedPay)
    {
        $failedPay->track($request);

        return response()->json(['error' => $request['message']]);
    }
}
