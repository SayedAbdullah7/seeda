<?php

namespace App\Http\Controllers\Api\Rate;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AddRateRequest;

class AddRateController  extends Controller 
{
    public function index()
    {
        return GeneralApiFactory('AddRateController',AddRateRequest::class);
    }
}
