<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UnPlaceOrderController extends Controller
{
    public function index()
    {
        return GeneralApiFactory('UnPlaceOrderController',Request::class);
    }
}
