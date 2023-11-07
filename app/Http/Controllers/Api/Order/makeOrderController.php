<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\makeOrderRequest;
use App\Models\Location;
use App\Models\Order;
use App\Models\OrderSentToDriver;
use App\Enums\orderStatus;
use App\Http\Resources\OrderResource;
use App\Http\Resources\locationResource;
use App\Response\ApiResponse;
use App\Events\Order\newOrderEvent;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class makeOrderController  extends Controller
{
    public function index()
    {
        return GeneralApiFactory('makeOrderController',makeOrderRequest::class);
    }
}
