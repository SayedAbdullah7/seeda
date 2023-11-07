<?php

namespace App\services\ActivityLog;

use App\Models\ActivityLog;

class ActivityLogObserve
{

    public function created($model)
    {
        if (auth()->user())
            $this->storeActivity("created",$model,auth()->user());
        else
            $this->storeActivity("created",$model,[]);
    }

    public function updated($model)
    {
        $this->storeActivity("updated",$model,auth()->user());
    }

    public function deleted($model)
    {
        $this->storeActivity("deleted",$model,auth()->user());
    }

    public function restored($model)
    {
        $this->storeActivity("restored",$model,auth()->user());
    }

    public function forceDeleted($model)
    {
        $this->storeActivity("forceDeleted",$model,auth()->user());
    }

    public function storeActivity($event, $model, $causer){

        $data = [
            "event"=>$event,
            "model"=>get_class($model),
            "model_id"=>$model->id,
        ];
        if ($causer != null){
            $data ["causer"]=get_class($causer);
            $data ["causer_id"]=$causer->id;
        }

        ActivityLog::create($data);
    }
}