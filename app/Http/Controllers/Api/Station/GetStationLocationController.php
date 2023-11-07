<?php

namespace App\Http\Controllers\Api\Station;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GetStationLocationController extends Controller
{
    public function index(){
        return GeneralApiFactory("GetStationLocationController",Request::class);
    }
}
