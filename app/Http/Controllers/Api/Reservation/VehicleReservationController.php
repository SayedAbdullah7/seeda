<?php

namespace App\Http\Controllers\Api\Reservation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VehicleReservationController extends Controller
{
    public function index(){
        return GeneralApiFactory("VehicleReservationController",Request::class);
    }
}
