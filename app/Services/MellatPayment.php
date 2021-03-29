<?php
namespace App\Services;

use App\Interfaces\Payment;

class MellatPayment implements Payment
{
    public function pay($request)
    {
        //redirect to Mellat gateway with with posted data
    }
}
