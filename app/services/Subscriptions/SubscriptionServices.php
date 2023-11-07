<?php

namespace App\services\Subscriptions;

use App\Models\Subscriber;
use App\Models\subscription;
use Carbon\Carbon;

class SubscriptionServices
{
    public static function addSubscriber($subscription , $user)
    {
        $check = Subscriber::where("user_id",$user->id)->first();
        if ($check){
            if ($check->subscriptions_id == $subscription->id)
                return self::renewSubscriber($subscription,$user);
            return self::upgrade($subscription , $user);
        }
        Subscriber::create([
            "user_id"=>auth()->id(),
            "subscriptions_id"=>$subscription->id,
            "start_date"=>now()->toDateString(),
            "end_date"=>now()->addDays($subscription->duration)->toDateString(),
            "is_active"=>1,
        ]);

        return true;
    }
    public static function renewSubscriber($subscription , $user){
        $subscriber = Subscriber::where("user_id",$user->id)->first();
        $endDate = $subscriber->end_date;
        $subscriber->update([
            "end_date"=>Carbon::make($endDate)->addDays($subscription->duration)->toDateString()
        ]);
        return true;
    }

    public static function upgrade($subscription,$user){
        $subscriber = Subscriber::where("user_id",$user->id)->first();
        $endDate = $subscriber->end_date;
        if ($endDate > now()->toDateString()){
            $subscriber->update([
                "subscriptions_id"=>$subscription->id,
                "end_date"=>now()->addDays($subscription->duration)->toDateString()
            ]);
            return true;
        }
        $subscriber->update([
            "subscriptions_id"=>$subscription->id,
            "end_date"=>Carbon::make($endDate)->addDays($subscription->duration)->toDateString()
        ]);
        return true;
    }
}
