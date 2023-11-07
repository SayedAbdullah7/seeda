<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Api\verifyOtpRequest;
use App\Http\Resources\userResource;
use Auth;

class verifyOtpController extends Controller
{
    public function index()
    {
        return GeneralApiFactory('verifyOtpController',verifyOtpRequest::class);
    }
}
