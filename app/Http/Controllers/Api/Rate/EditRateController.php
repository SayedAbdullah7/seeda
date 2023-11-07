<?php

namespace App\Http\Controllers\Api\Rate;

use App\Http\Controllers\Controller;
use App\Http\Requests\EditRateRequest;

class EditRateController extends Controller
{
    public function index()
    {
        return GeneralApiFactory('EditRateController',EditRateRequest::class);
    }
}
