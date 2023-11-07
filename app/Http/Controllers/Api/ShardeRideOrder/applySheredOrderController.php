<?php

namespace App\Http\Controllers\Api\ShardeRideOrder;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\applySheredOrderRequest;

class applySheredOrderController extends Controller
{
    public function index(){
        return GeneralApiFactory('ApplySheredOrderController',applySheredOrderRequest::class);
    }
}
