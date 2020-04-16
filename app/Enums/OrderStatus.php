<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class OrderStatus extends Enum
{
    const waiting = 0;
    const paid =   1;
    const cancelled =   2;
    const accepted = 3;
    const shipped = 4;
    const rejected = 5;
    const returned = 6;
    const delivered = 7;
}
