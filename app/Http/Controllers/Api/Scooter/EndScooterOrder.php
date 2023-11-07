<?php

namespace App\Http\Controllers\Api\Scooter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EndScooterOrder extends Controller
{
    public function index(){
        return GeneralApiFactory("EndScooterOrderController",Request::class);
    }
}
