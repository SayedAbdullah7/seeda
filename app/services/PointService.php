<?php

namespace App\services;

use App\Http\Resources\PointResource;
use App\Models\Points;
use App\Models\Transaction;
use App\Response\ApiResponse;

class PointService
{
    public  function deposit($amount){
        $point = Points::where("user_id",auth()->id())->first();
        if ($point->points < $amount)
            return false;

        $newBalance = $point->points - $amount;
        $point->update(["points"=>$newBalance]);

        $this->PointTransaction($amount,Points::class,$point->id,"deposit");

        return true;
    }

    public  function charge($amount){
        $point = Points::where("user_id",auth()->id())->first();

        $newBalance = $point->points + $amount;
        $point->update(["points"=>$newBalance]);

        $this->PointTransaction($amount,Points::class,$point->id,"charge");

        return true;
    }

    public function PointTransaction($amount,$model,$id,$type){
        Transaction::create([
            "amount"=>$amount,
            "transable_id"=>$id,
            "transable_type"=>$model,
            "type"=>$type
        ]);
    }

    static function createPoint($id){
        Points::create(["user_id"=>$id,"points"=>0]);
        return true;
    }

    public function getPointData($id){
        $point =  Points::with("Transaction")->where("user_id",$id)->first();

        if ($point) {
            $response = new ApiResponse(200, __('api.your Wallet data retrieved successfully'), ['Points' => new PointResource($point)]);
            return $response->send();
        }

        $response = new ApiResponse(400, __('api.not have wallet make it first'), ['wallet' => []]);
        return $response->send();
    }
}