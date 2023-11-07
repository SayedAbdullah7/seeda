<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GetActiveOrder extends Controller
{
    public function index(){
        return GeneralApiFactory("GetActiveOrderController",Request::class);
    }
}
