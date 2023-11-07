<?php

namespace App\Http\Controllers\Api\Scooter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderScooterController extends Controller
{
    public function index(){
        return GeneralApiFactory("OrderScooterController",Request::class);
    }
}
