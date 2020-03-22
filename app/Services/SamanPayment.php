<?php

namespace App\Services;

use App\Interfaces\payment;

class SamanPayment implements payment
{

    public function pay($request)
    {
        $response = array(
            '‫‪State‬‬'         => 'Success',
            '‫‪StateCode‬‬'     => 200,
            'description'   => "everyThing's Ok , paid successfully",
            '‫‪MID‬‬'           => 12345,
            '‫‪RefNum‬‬'        => 'dgw456',
            '‫‪TRACENO‬‬'       => 'dfjntg8954'
        );

        return response()->json(compact('response'));
    }
}
