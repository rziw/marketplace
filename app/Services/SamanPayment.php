<?php

namespace App\Services;

use App\Interfaces\Payment;

class SamanPayment implements Payment
{

    public function pay($request)
    {
        $response = [
            'State'         => 'Success',
            'StateCode'     =>  200,
            'message'       => "everyThing's Ok , paid successfully",
            'MID'           => '12345',
            'RefNum'        => 'dgw456',
            'TRACENO'       => 'dfjntg8954',
        ];

        return json_encode($response);
    }
}
