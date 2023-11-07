<?php

namespace App\Enums;

use Illuminate\Validation\Rules\Enum;

Enum PaymentKey :string
{
    case wallet = 'wallet';
    case order = 'order';
}
