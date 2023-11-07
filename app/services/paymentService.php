<?php

namespace App\services;

use App\Http\Resources\RateResrouce;
use App\Integrations\payment\factory\newStripe;
use App\Models\Points;
use App\Models\Wallet;
use App\Response\ApiResponse;
use Stripe\StripeClient;

class paymentService
{
    public static function pay($amount,$paymenttype,$order){
        $key =$paymenttype->key;
        return self::$key($amount,$order);
    }

    static function wallet($amount,$order){
        $userWallet = Wallet::where("user_id",auth()->id())->first();
        if ($userWallet->balance >= $amount){
            (new walletService())->deposit($amount);
            $order->update(["payment_status"=>1]);
            $response = new ApiResponse(200,__('api.successfully Paid',),[]);
            return $response->send();
        }
        $response = new ApiResponse(406,__("api.balance low can't complete pay",),[]);
        return $response->send();
    }

    static function points($amount,$order){
        $userPoints = Points::where("user_id",auth()->id())->first();
        $value = $userPoints->points* 1 ; // 1 value for point will get from admin configuration
        if ($value >= $amount){
            (new PointService())->deposit($amount);
            $order->update(["payment_status"=>1]);
            $response = new ApiResponse(200,__('api.successfully Paid',),[]);
            return $response->send();
        }

        $response = new ApiResponse(406,__("api.balance low can't complete pay",),[]);
        return $response->send();
    }


    static function stripe($amount){
        $url = route("stripe.get");

        $response = new ApiResponse(200,__('api.url for Pay Generated successfully',),[
            "url"=>$url
        ]);
        return $response->send();
    }

    public function noon(){

    }

    public function masterCard(){

    }

    public function Paymob(){

    }

}