<?php
namespace App\Services;

use App\Interfaces\payment;

class MellatPayment implements payment
{

    public function pay($request)
    {
        $response = array(
            '‫‪State‬‬'         => 'Fail',
            '‫‪StateCode‬‬'     => 500,
            'description'   => "Server is down LOL",
            '‫‪MID‬‬'           => 12345,
            '‫‪RefNum‬‬'        => 'dgw456',
            '‫‪TRACENO‬‬'       => '23434kljkl'
        );

        return response()->json(compact('response'));
    }
}
