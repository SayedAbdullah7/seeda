<?php

namespace App\Http\Controllers\Api\geofences;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class checkUserInZoneController extends Controller
{
    public function index(){
        return GeneralApiFactory("checkUserInZoneController",Request::class);
    }
}
