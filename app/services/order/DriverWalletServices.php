<?php

namespace App\services\order;

use App\services\walletService;

class DriverWalletServices
{

    public static function endOrderChargeWallet($order,$data){
        if (in_array($order->payment_type_id,[1,2])){
            (new walletService())->charge(($data["price"]-$data["captainTax"]));
        } elseif ($order->payment_type_id == 3){
            if($data["discount"]>0){
                $priceAfterDiscount = $data["userPrice"] - $data["discount"];
                $ActualDriverPrice = $data["price"] - $data["captainTax"];
                $amount = $ActualDriverPrice-$priceAfterDiscount;
                (new walletService())->charge(($amount));
            }else{
                (new walletService())->charge((($data["userTax"]+$data["captainTax"])*-1));
            }
        }
    }
}
