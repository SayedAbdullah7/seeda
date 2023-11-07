<?php

namespace App\Enums;

use Illuminate\Validation\Rules\Enum;

Enum PaymentWays :string
{
    case mobileWallet = 'mobileWallet';
    case card = 'card';
}
