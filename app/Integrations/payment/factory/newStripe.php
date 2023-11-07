<?php

namespace App\Integrations\payment\factory;

use PhpParser\Node\Expr\Cast\String_;
use Stripe\StripeClient;

class newStripe
{
    private String $apiToken;
    private $amount;
    private String $currency;

    public function __construct($currency,$amount){
        $this->apiToken = "sk_live_cpRfcChiPXqvpbO5PvIFLUr300uSfBJJCs"; // will get from admin configure
        $this->amount = $amount;
        $this->currency = $currency;
    }
    public function pay()
    {
        $stripe = new StripeClient($this->apiToken);

        $checkout_session = $stripe->checkout->sessions->create([
            'line_items' => [[
                'price_data' => [
                    'currency' => $this->currency ?? "usd",
                    'product_data' => [
                        'name' => 'T-shirt',
                    ],
                    'unit_amount' => $amount?? 100,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => 'http://127.0.0.1:8000/success',
            'cancel_url' => 'http://127.0.0.1:8000/cancel',
        ]);

        return $checkout_session->url;
    }


}