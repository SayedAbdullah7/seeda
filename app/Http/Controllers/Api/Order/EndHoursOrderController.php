<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EndHoursOrderController extends Controller
{
    public function index(){
        return GeneralApiFactory("EndHoursOrderController",Request::class);
    }
}
