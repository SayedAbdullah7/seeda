<?php
namespace App\Enums;

use Illuminate\Validation\Rules\Enum;

Enum paymentStatus:string
{
    case pending = 'pending';
    case success = 'success';

    case failed = 'failed';

}
