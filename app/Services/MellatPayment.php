<?php
namespace App\Services;

use App\Interfaces\Payment;

class MellatPayment implements Payment
{

    public function pay($request)
    {
        $response = array(
            '‫‪State‬‬'         => 'Fail',
            '‫‪StateCode‬‬'     => 500,
            'message'   => "Server is down LOL",
            '‫‪MID‬‬'           => 12345,
            '‫‪RefNum‬‬'        => 'dgw456',
            '‫‪TRACENO‬‬'       => '23434kljkl'
        );

        return response()->json(compact('response'));
    }
}
