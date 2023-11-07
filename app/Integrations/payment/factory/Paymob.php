<?php

namespace App\Integrations\payment\factory;

use App\EnumStatus\paymentStatus;
use App\EnumStatus\PaymentWays;
use App\Jobs\CallPaymentUrl;
use App\Models\Card;
use App\Models\PaymentTranscation;
use App\Models\User;
use App\services\HttpService;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Paymob
{

    private HttpService $Service;


    public function __construct()
    {
        $this->Service = new httpService();
    }



    public function Authentication(){
        $body=["api_key"=> "ZXlKaGJHY2lPaUpJVXpVeE1pSXNJblI1Y0NJNklrcFhWQ0o5LmV5SndjbTltYVd4bFgzQnJJam8zTWpVM05qSXNJbTVoYldVaU9pSnBibWwwYVdGc0lpd2lZMnhoYzNNaU9pSk5aWEpqYUdGdWRDSjkuSUVNNHZFanhSNVY4bFZGT1Q5Sy1wX0pnVERPZGttbUlVRlBUNjRUbG9USEItOVQxNHAtZHE2dVc4eXJsN0c1a0pqUE9hUGFMellPd3FyanhMQkhTakE="];
        $data = $body;

        return $this->Service->POST("https://accept.paymob.com/api/auth/tokens",['Content-Type' => 'application/json'],$data);
    }

    public function orderRegistrationApi($token , $price){
        $body=[
            "auth_token"=>$token,
            "delivery_needed"=> "false",
            "amount_cents"=> $price,
            "items"=> [[
                "name"=> "Shipment",
                "amount_cents"=> $price,
                "description"=> "Shipment",
                "quantity"=> "1"
            ]]];
        $data = $body;

        return $this->Service->POST('https://accept.paymob.com/api/ecommerce/orders',['Content-Type' => 'application/json'],$data);
    }

    public function paymentKeyRequest(User $user,$prices){
        $auth = $this->Authentication();
        $auth = $auth->json();
        $token = $auth['token'];
        $price = (int)($prices * 100);
        $order_id = $this->orderRegistrationApi($token , $price)->json();

        $user_Array =explode(" ",$user->name);

        if (count($user_Array) > 1){
            $billingData = [
                "apartment"=> "NA",
                "email"=> $user->email,
                "extra_description"=> "".appKey(),
                "floor"=> "NA",
                "first_name"=> $user_Array[0],
                "street"=> "NA",
                "building"=> "NA",
                "phone_number"=> $user->phone,
                "shipping_method"=> "NA",
                "postal_code"=> "NA",
                "city"=> "NA",
                "country"=> "NA",
                "last_name"=> $user_Array[1],
                "state"=> "NA",
            ];
        }else{
            $faker = Factory::create();
            $fakerName =  $faker->name;
            $billingData = [
                "apartment"=> "NA",
                "email"=> $user->email,
                "extra_description"=> "".appKey(),
                "floor"=> "NA",
                "first_name"=> $user->name,
                "street"=> "NA",
                "building"=> "NA",
                "phone_number"=> $user->phone,
                "shipping_method"=> "NA",
                "postal_code"=> "NA",
                "city"=> "NA",
                "country"=> "NA",
                "last_name"=> $fakerName,
                "state"=> "NA",
            ];
        }
        $body = [
            'auth_token'=> $token,
            'amount_cents'=> $price,
            'expiration'=> 3600,
            'order_id	'=> $order_id['id'],
            'billing_data'=> $billingData,
            'currency'=> 'EGP',
            'integration_id'=> 3682821,
        ];
        $data = $body;
        $res = $this->Service->POST('https://accept.paymob.com/api/acceptance/payment_keys',['Content-Type' => 'application/json'],$data);
        $pay = $res->json();
        return[
            "order"=>$order_id,
            "token"=>$pay["token"],
        ];
    }

    public function paymentSavedCardKeyRequest(User $user,$prices){
        $auth = $this->Authentication();
        $auth = $auth->json();
        $token = $auth['token'];
        $price = (int)($prices * 100);
        $order_id = $this->orderRegistrationApi($token , $price)->json();

        $user_Array =explode(" ",$user->name);

        if (count($user_Array) > 1){
            $billingData = [
                "apartment"=> "NA",
                "email"=> $user->email,
                "extra_description"=> "".appKey(),
                "floor"=> "NA",
                "first_name"=> $user_Array[0],
                "street"=> "NA",
                "building"=> "NA",
                "phone_number"=> $user->phone,
                "shipping_method"=> "NA",
                "postal_code"=> "NA",
                "city"=> "NA",
                "country"=> "NA",
                "last_name"=> $user_Array[1],
                "state"=> "NA",
            ];
        }else{
            $faker = Factory::create();
            $fakerName =  $faker->name;
            $billingData = [
                "apartment"=> "NA",
                "email"=> $user->email,
                "extra_description"=> "".appKey(),
                "floor"=> "NA",
                "first_name"=> $user->name,
                "street"=> "NA",
                "building"=> "NA",
                "phone_number"=> $user->phone,
                "shipping_method"=> "NA",
                "postal_code"=> "NA",
                "city"=> "NA",
                "country"=> "NA",
                "last_name"=> $fakerName,
                "state"=> "NA",
            ];
        }
        $body = [
            'auth_token'=> $token,
            'amount_cents'=> $price,
            'expiration'=> 3600,
            'order_id	'=> $order_id['id'],
            'billing_data'=> $billingData,
            'currency'=> 'EGP',
            'integration_id'=> 4014190,
        ];
        $data = $body;
        $res = $this->Service->POST('https://accept.paymob.com/api/acceptance/payment_keys',['Content-Type' => 'application/json'],$data);
        $pay = $res->json();
        return[
            "order"=>$order_id,
            "token"=>$pay["token"],
        ];
    }

    public function paymentKeyMobileRequest(User $user,$prices){
        $auth = $this->Authentication();
        $auth = $auth->json();
        $token = $auth['token'];
        $price = (int)($prices * 100);
        $order_id = $this->orderRegistrationApi($token , $price)->json();

        $user_Array =explode(" ",$user->email);

        if (count($user_Array) > 1){
            $billingData = [
                "apartment"=> "NA",
                "email"=> $user->email,
                "extra_description"=> "".appKey(),
                "floor"=> "NA",
                "first_name"=> $user_Array[0],
                "street"=> "NA",
                "building"=> "NA",
                "phone_number"=> $user->phone,
                "shipping_method"=> "NA",
                "postal_code"=> "NA",
                "city"=> "NA",
                "country"=> "NA",
                "last_name"=> $user_Array[1],
                "state"=> "NA",
            ];
        }else{
            $faker = Factory::create();
            $fakerName =  $faker->name;
            $billingData = [
                "apartment"=> "NA",
                "email"=> $user->email,
                "extra_description"=> "".appKey(),
                "floor"=> "NA",
                "first_name"=> $user->name,
                "street"=> "NA",
                "building"=> "NA",
                "phone_number"=> $user->phone,
                "shipping_method"=> "NA",
                "postal_code"=> "NA",
                "city"=> "NA",
                "country"=> "NA",
                "last_name"=> $fakerName,
                "state"=> "NA",
            ];
        }

        $body = [
            'auth_token'=> $token,
            'amount_cents'=> $price,
            'expiration'=> 3600,
            'order_id	'=> $order_id['id'],
            'billing_data'=> $billingData,
            'currency'=> 'EGP',
            'integration_id'=> 3714489,
        ];
        $data = $body;
        $res = $this->Service->POST('https://accept.paymob.com/api/acceptance/payment_keys',['Content-Type' => 'application/json'],$data);
        $pay = $res->json();
        return[
            "order"=>$order_id,
            "token"=>$pay["token"],
        ];
    }

    public function saveCard(User $user,$prices,$way,$key,$user_order_id=null,$is_save=0){
        $paymentKeyRequest = $this->paymentKeyRequest($user,$prices);
        $this->createPaymentOrder( $paymentKeyRequest["order"],$user,$prices,$way,$key,$user_order_id,$is_save);
        return[ "url"=>"https://accept.paymob.com/api/acceptance/iframes/744913?payment_token=".$paymentKeyRequest["token"],"sad"=>$paymentKeyRequest["order"]];
    }

    public function payWithToken(Card $card,User $user,$prices,$way,$key,$user_order_id=null,$is_save=0,$order_ended=0,$is_reserve=0,$time=0)
    {
        $paymentKey = $this->paymentSavedCardKeyRequest($user, $prices);
        $data = [
            "source" => [
                "identifier" => $card->token,
                "subtype" => "TOKEN"
            ],
            "payment_token" => $paymentKey['token']
        ];

        $rsp = $this->Service->POST('https://accept.paymob.com/api/acceptance/payments/pay', ['Content-Type' => 'application/json'], $data)->json();
        $this->createPaymentOrder($rsp['order'], $user, $prices, $way, $key, $user_order_id, $is_save, $order_ended, $is_reserve, $time);

        CallPaymentUrl::dispatch($rsp["redirection_url"])->delay(Carbon::now()->addSeconds(30));
        return $rsp["redirection_url"];
    }

    public function payWithPhone($phone,User $user,$prices,$way,$key,$user_order_id=null,$is_save=0,$order_ended=0)
    {
        $paymentKey = $this->paymentKeyMobileRequest($user,$prices);
        $data = [
            "source" => [
                "identifier"=> $phone,
                "subtype"=> "WALLET"
            ],
            "payment_token" => $paymentKey['token']
        ];

        $rsp = $this->Service->POST('https://accept.paymob.com/api/acceptance/payments/pay',['Content-Type' => 'application/json'],$data)->json();
        $this->createPaymentOrder($rsp['order'],$user,$prices,$way,$key,$user_order_id,$is_save,$order_ended);

        return $rsp["redirect_url"];
    }

    public function createPaymentOrder($order,User $user,$prices,$way,$key,$user_order_id=null,$is_save=0,$order_ended=0,$is_reserve=0,$time=0){

        PaymentTranscation::create([
            "order_id"=>is_array($order)?$order["id"]:$order,
            "user_id"=>$user->id,
            "user_order_id"=>$user_order_id,
            "status"=>paymentStatus::pending,
            "amount"=>$prices,
            "mobile_wallet"=>($way == paymentWays::mobileWallet)?1:0,
            "card"=>($way == paymentWays::card)?1:0,
            "is_wallet"=>($key == "wallet")?1:0,
            "is_order"=>($key == "order")?1:0,
            "is_save"=>$is_save,
            "is_reserve"=>$is_reserve,
            "order_ended"=>$order_ended,
            "time"=>$time,
            "appKey"=>appKey(),
        ]);
    }

    public function getTrancaction ($id){
        $auth = $this->Authentication();
        $auth = $auth->json();
        $token = $auth['token'];
        return Http::withToken($token)->withHeaders([
        ])->get("https://accept.paymob.com/api/acceptance/transactions/".$id,[]);
    }

    public function Refund($transaction_id,$amount){
        $auth = $this->Authentication();
        $auth = $auth->json();
        $token = $auth['token'];
        $data = [
            "auth_token"=> $token,
            "transaction_id"=> $transaction_id,
            "amount_cents"=> $amount
        ];
        $resp = Http::withToken($token)->withHeaders(['Content-Type' => 'application/json'])
            ->post("https://accept.paymob.com/api/acceptance/void_refund/refund",$data);
        Log::error($resp,$data);
    }
}
