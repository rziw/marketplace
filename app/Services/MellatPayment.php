<?php
namespace App\Services;

use App\Interfaces\Payment;

class MellatPayment implements Payment
{

    public function pay($request)
    {
        $response = [
            'State'         => 'Fail',
            'StateCode‬'     => '500',
            'message'       => "Server is down LOL",
            'MID'           => '12345',
            'RefNum'        => 'dgw456',
            'TRACENO'       => 'dfjntg8954',
        ];

        return json_encode($response);
    }
}
