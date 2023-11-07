<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CheckRegisterController extends Controller
{
    public function index(){
        return GeneralApiFactory("CheckRegisterController",Request::class);
    }
}
