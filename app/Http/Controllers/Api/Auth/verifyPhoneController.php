<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\Api\verifyPhoneRequest;
use App\Integrations\sms\facrotyMethodSms;
use App\Http\Resources\userResource;

class verifyPhoneController extends Controller
{
    public function index()
    {
        return GeneralApiFactory('verifyPhoneController',verifyPhoneRequest::class);
    }
}
