<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\GetMyOrderRequest;
use Illuminate\Support\Facades\Request;

//use App\Http\Requests\historyOrderRequest;

class historyOrdersController extends Controller
{
    public function index(){
        return GeneralApiFactory('historyOrdersController',Request::class);
    }
}
