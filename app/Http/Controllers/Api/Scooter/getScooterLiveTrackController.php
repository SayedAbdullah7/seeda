<?php

namespace App\Http\Controllers\Api\Scooter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class getScooterLiveTrackController extends Controller
{
    public function index(){
        return GeneralApiFactory("getScooterLiveTrackController",Request::class);
    }
}
