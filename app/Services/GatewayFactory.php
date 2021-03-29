<?php

namespace App\Services;

class GatewayFactory
{
    public function build(string $gateway)
    {
        switch ($gateway) {
            case $gateway == 'saman':
                return new SamanPayment();
                break;
            case $gateway == 'mellat':
                return new MellatPayment();
                break;
            default:
                throw new \Exception("Gateway is not supported", 422);
        }
    }
}
