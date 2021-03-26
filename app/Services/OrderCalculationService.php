<?php

namespace App\Services;

class OrderCalculationService
{
    public function calculatePriceAction(float $productPrice, int $requestedCount)
    {
        return $requestedCount * $productPrice;
    }
}
