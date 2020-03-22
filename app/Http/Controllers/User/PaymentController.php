<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\MellatPayment;
use App\Services\SamanPayment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    private $samanGateway;
    private $mellatGateway;

    public function __construct()
    {
        $this->samanGateway = new SamanPayment();
        $this->mellatGateway = new MellatPayment();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->only(['order_id', 'gateway']);

        $payment = ($request->gateway == 'saman') ? $this->samanGateway:
            $this->mellatGateway;
        $pay_result = $payment->pay($request);

        return response()->json(['message'=> 'You have successfully updated your cart.']);
    }
}
