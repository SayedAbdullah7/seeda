<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;

class ChangePasswordController extends Controller
{
    public function index(){
        return GeneralApiFactory('ChangePasswordController',ChangePasswordRequest::class);
    }
}
