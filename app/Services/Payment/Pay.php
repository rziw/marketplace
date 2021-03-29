<?php

namespace App\Services\Payment;

use App\Models\Payment;
use Illuminate\Http\Request;

class Pay
{
    protected function storePayment(Request $request)
    {
        $input['status'] = $request['State'];
        $input['tracking_code'] = $request['TRACENO'];
        $input['price'] = $request['amount'];

        Payment::create($input);
    }
}
