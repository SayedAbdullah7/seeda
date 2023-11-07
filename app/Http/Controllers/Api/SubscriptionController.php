<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SubscribRequest;
use App\Models\Subscription;
use App\Response\ApiResponse;
use App\services\Subscription\SubscriptionServices;
use Illuminate\Support\Facades\Request;

class SubscriptionController extends Controller
{
    public function index(){
        $sub = Subscription::get();

        $response = new ApiResponse(200,__('api.subscription retrieved successfully'),[
            "subscription"=>$sub
        ]);
        return $response->send();
    }

    public function Subscriber(SubscribRequest $request){
        $Subscription = Subscription::find($request->subscriptions_id);
        if (SubscriptionServices::addSubscriber($Subscription,auth()->user())){
            $response = new ApiResponse(200,__('api.subscribe created   successfully'),[]);
            return $response->send();
        }
        $response = new ApiResponse(400,__('api.error in adding subscribe'),[]);
        return $response->send();
    }

    public function upgrade(SubscribRequest $request){
        $Subscription = Subscription::find($request->subscriptions_id);
        if (SubscriptionServices::addSubscriber($Subscription,auth()->user())){
            $response = new ApiResponse(200,__('api.subscribe created   successfully'),[]);
            return $response->send();
        }
        $response = new ApiResponse(400,__('api.error in adding subscribe'),[]);
        return $response->send();
    }

    public function renew(SubscribRequest $request){
        $Subscription = Subscription::find($request->subscriptions_id);
        if (SubscriptionServices::addSubscriber($Subscription,auth()->user())){
            $response = new ApiResponse(200,__('api.subscribe created   successfully'),[]);
            return $response->send();
        }
        $response = new ApiResponse(400,__('api.error in adding subscribe'),[]);
        return $response->send();
    }
}

