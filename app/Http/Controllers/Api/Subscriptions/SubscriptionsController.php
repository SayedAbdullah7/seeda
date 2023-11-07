<?php

namespace App\Http\Controllers\Api\Subscriptions;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SubscribRequest;
use App\Models\Subscriber;
use App\Models\Subscription;
use App\Response\ApiResponse;
use App\services\Subscriptions\SubscriptionServices;
use Illuminate\Http\Request;

class SubscriptionsController extends Controller
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
            $response = new ApiResponse(200,__('api.subscribe created successfully'),[]);
            return $response->send();
        }
        $response = new ApiResponse(400,__('api.error in adding subscribe'),[]);
        return $response->send();
    }

    public function SubscriberCheck(){
        $subscriber= Subscriber::with("Subscription")->where("user_id",auth()->id())->first();
        if ($subscriber){
            return (new ApiResponse(200,__("api.SubscribeFounded"),[
                "flag"=>true,
                "is_active"=> ($subscriber->end_date > now()->toDateString()),
                "data"=>$subscriber,
            ]))->send();
        }else{
            return (new ApiResponse(200,__("api.NoSubscribeFounded"),[
                "flag"=>false,
                "is_active"=>false,
                "data"=>null,
            ]))->send();
        }
    }

    public function upgrade(SubscribRequest $request){
        $Subscription = Subscription::find($request->subscriptions_id);
        if (SubscriptionServices::upgrade($Subscription,auth()->user())){
            $response = new ApiResponse(200,__('api.subscribe upgraded your Package successfully'),[]);
            return $response->send();
        }
        $response = new ApiResponse(400,__('api.error in adding subscribe'),[]);
        return $response->send();
    }

    public function renew(SubscribRequest $request){
        $Subscription = Subscription::find($request->subscriptions_id);
        if (SubscriptionServices::renewSubscriber($Subscription,auth()->user())){
            $response = new ApiResponse(200,__('api.subscribe Renew your Package successfully'),[]);
            return $response->send();
        }
        $response = new ApiResponse(400,__('api.error in Renew subscription maybe Deference subscriptions Id'),[]);
        return $response->send();
    }
}
