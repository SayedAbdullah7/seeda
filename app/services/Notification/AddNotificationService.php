<?php

namespace App\services\Notification;

use App\Models\Notification;
use App\Models\Notify;

class AddNotificationService
{
    public function Store($users,$title,$content){
        $notify = Notification::create([
            "title"=>$title,
            "content"=>$content,
            "appKey"=>appKey(),
        ]);

        $this->attachUser($users,$notify);
        notificationServices::sendAllNotifications($users->pluck("fcm")->toArray(),$title,$content);
        return 1;
    }

    public function StoreAfterPayment($users,$title,$content){
        $notify = Notification::create([
            "title"=>$title,
            "content"=>$content,
            "appKey"=>$users->pluck("appKey")->toArray()[0],
        ]);

        $this->attachUser($users,$notify);
        notificationServices::sendAllNotificationsWithAppKey($users->pluck("fcm")->toArray(),$title,$content,$users->pluck("appKey")->toArray()[0]);
        return 1;
    }

    private function attachUser($users, $notify)
    {
        foreach ($users as $user){
            Notify::create([
                "user_id"=>$user->id,
                "notification_id"=>$notify->id,
            ]);
        }
    }
}
