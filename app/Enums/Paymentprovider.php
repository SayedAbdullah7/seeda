<?php

namespace App\Enums;

use Illuminate\Validation\Rules\Enum;

Enum Paymentprovider :string
{
    case user = 'Paymob';
    case order = 'stripe';
}
