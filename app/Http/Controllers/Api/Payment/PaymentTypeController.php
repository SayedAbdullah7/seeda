<?php

namespace App\Http\Controllers\Api\Payment;

use App\Http\Controllers\Controller;
use App\Models\PaymentType;
use App\Response\ApiResponse;

class PaymentTypeController extends Controller
{
    public function index(){
        $paymentTypes = PaymentType::where("appKey",appKey())->select("id", "key", "link")->get();


        $response = new ApiResponse(200,__("api.paymentTypes retrieved successfully",),[
            "paymentTypes"=>$paymentTypes
        ]);

        return $response->send();
    }
}
