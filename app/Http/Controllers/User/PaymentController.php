<?php

namespace App\Http\Controllers\User;

use App\Helpers\OrderHandler;
use App\Helpers\ProductQuantityChanging;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\PaymentRequest;
use App\Models\Payment;
use App\Repositories\OrderRepository;
use App\Services\MellatPayment;
use App\Services\SamanPayment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    private $samanGateway;
    private $mellatGateway;
    private $orderHandler;
    private $orderRepository;
    private $updateProductQuantity;

    public function __construct()
    {
        $this->samanGateway = new SamanPayment();
        $this->mellatGateway = new MellatPayment();
        $this->orderHandler = new OrderHandler();
        $this->orderRepository = new OrderRepository();
        $this->updateProductQuantity = new ProductQuantityChanging();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function payOrder(PaymentRequest $request)
    {
        $order = isset($request->order) ? $request->order : $this->orderRepository->get($request->order_id);

        $payment = ($request->gateway == 'saman') ? $this->samanGateway:
            $this->mellatGateway;

        $request->amount = $order->total_price;

        $pay_result = json_decode($payment->pay($request), true);

        $this->storePayment($pay_result, $request, $order);

        if($pay_result['StateCode'] == 200) {

            $this->orderHandler->updateOrderStatus($order, 'paid');
            $this->updateProductQuantity->updateQuantity($order);
            return response()->json(['message' => 'you successfully paid the order']);

        }

        return response()->json(['error'=> $pay_result['message']]);
    }

    private function storePayment($pay_result, Request $request, $order)
    {
        $input = $request->only(['order_id', 'gateway']);
        $input['status'] = $pay_result['State'];
        $input['tracking_code'] = $pay_result['TRACENO'];
        $input['price'] = $request->amount;

        Payment::create($input);
    }
}
