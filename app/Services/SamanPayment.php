<?php

namespace App\Services;

use App\Interfaces\Payment;

class SamanPayment implements Payment
{

    public function pay($request)
    {
        //redirect to Saman gateway with posted data
    }
}
