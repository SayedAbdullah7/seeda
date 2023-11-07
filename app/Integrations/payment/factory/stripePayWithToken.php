<?php

namespace App\Integrations\payment\factory;

use Exception;

class stripePayWithToken
{
    private $data;

    public function __construct($data){
        $this->data = $data;
    }

    public function payWithToken(){
        $stripe = new \Stripe\StripeClient(
            'sk_live_cpRfcChiPXqvpbO5PvIFLUr300uSfBJJCs'
        );

        try {
            $charge = $stripe->charges->create([
                'amount' => $this->data["amount"],
                'currency' => 'usd',
                'customer' => $this->data["token"],
            ]);
        }catch (Exception $exception){
            return null;
        }

        return $charge;
    }
}