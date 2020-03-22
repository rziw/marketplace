<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class OrderStatus extends Enum
{
    const waiting =   0;
    const paid =   1;
    const accepted = 2;
    const shipped = 3;
    const rejected = 4;
    const returned = 5;
}
