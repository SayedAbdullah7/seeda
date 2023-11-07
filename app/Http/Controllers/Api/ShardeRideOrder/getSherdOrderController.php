<?php

namespace App\Http\Controllers\Api\ShardeRideOrder;

use App\Http\Controllers\Controller;
use App\Http\Requests\historyOrderRequest;
use Illuminate\Http\Request;

class getSherdOrderController extends Controller
{
    public function index(){
        return GeneralApiFactory('getSherdOrderController',Request::class);
    }
}
