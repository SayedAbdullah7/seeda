<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\makeOrderRequest;
use Illuminate\Http\Request;

class makeHoursOrderController extends Controller
{
    public function index()
    {
        return GeneralApiFactory('makeHoursOrderController',Request::class);
    }
}
