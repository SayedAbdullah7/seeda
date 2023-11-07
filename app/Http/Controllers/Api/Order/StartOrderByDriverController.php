<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Order\StartOrderByDriverRequest;

class StartOrderByDriverController extends Controller
{
    public function index()
    {
        return GeneralApiFactory('StartOrderByDriverController',StartOrderByDriverRequest::class);
    }
}
