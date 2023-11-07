<?php

namespace App\services\CancelOrder;

use App\services\walletService;

class CancelUserFees
{
    public static function UserCancelPenalty($order){
        $penalty = 20 ;//will get from configuration
        (new \App\services\walletService)->depositFromUser($penalty,$order->user_id);
        (new \App\services\walletService)->chargeAnthoerUser($penalty,$order->driver->user_id);
    }
}
