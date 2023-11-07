<?php

namespace App\Integrations\payment\factory;

use Mockery\Exception;

class StripSaveCard
{
    private $data;

    public function __construct($data){
        $this->data = $data;
    }
    public function savecard(){
        \Stripe\Stripe::setApiKey($this->data["token"]);

        $token = \Stripe\Token::create([
            "card" => [
                "number" => $this->data["card_number"],
                "exp_month" => $this->data["card_expiration_month"],
                "exp_year" => $this->data["card_expiration_year"],
                "cvc" => $this->data["card_security_code"]
              ]
            ]);

        $customer = \Stripe\Customer::create([
            'source' => $token->id,
            'email' => auth()->user()->email??null,
        ]);

        try {
             \Stripe\Charge::create([
                'amount' => 1000,
                'currency' => 'usd',
                'customer' => $customer->id,
            ]);
        }catch (Exception $exception){
            return null;
        }

        return $customer->id;
    }

}