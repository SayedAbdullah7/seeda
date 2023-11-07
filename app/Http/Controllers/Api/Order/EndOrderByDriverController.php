<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Order\EndOrderByDriverRequest;

class EndOrderByDriverController extends Controller
{
    public function index()
    {
        return GeneralApiFactory('EndOrderByDriverController',EndOrderByDriverRequest::class);
    }
}
