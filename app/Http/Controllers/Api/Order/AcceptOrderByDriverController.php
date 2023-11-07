<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Order\AcceptOrderByDriverRequest;

class AcceptOrderByDriverController extends Controller
{
    public function index()
    {
        return GeneralApiFactory('AcceptOrderByDriverController',AcceptOrderByDriverRequest::class);
    }
}
