<?php

namespace App\services;

use App\Http\Resources\WalletResource;
use App\Models\OrderDetails;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Transaction;
use App\Response\ApiResponse;
use App\services\Notification\AddNotificationService;

class walletService
{

    public  function deposit($amount){
        $wallet = Wallet::where("user_id",auth()->id())->first();

        $newBalance = $wallet->balance - $amount;
        $wallet->update(["balance"=>$newBalance]);

        $this->WalletTransaction($amount,Wallet::class,$wallet->id,"deposit");

        return true;
    }

    public  function depositFromUser($amount,$id){
        $wallet = Wallet::where("user_id",$id)->first();

        $newBalance = $wallet->balance - $amount;
        $wallet->update(["balance"=>$newBalance]);

        $this->WalletTransaction($amount,Wallet::class,$wallet->id,"deposit");

        return true;
    }

    public  function charge($amount){
        $wallet = Wallet::where("user_id",auth()->id())->first();

        $newBalance = $wallet->balance + $amount;
        $wallet->update(["balance"=>$newBalance]);

        $this->WalletTransaction($amount,Wallet::class,$wallet->id,"charge");

        return true;
    }

    public  function chargeToAnthoerUser($amount,$id){
        $wallet = Wallet::where("user_id",$id)->first();
        $user = User::where("id",$id)->get();
        $newBalance = $wallet->balance + $amount;
        $wallet->update(["balance"=>$newBalance]);

        $this->WalletTransaction($amount,Wallet::class,$wallet->id,"charge");

        (new AddNotificationService())->Store($user,"Add Remain to Wallet",$amount." is Added to your walletBalance");

        return true;
    }
    public  function chargeAnthoerUser($amount,$id){
        $wallet = Wallet::where("user_id",$id)->first();
        $user = User::where("id",$id)->get();
        $newBalance = $wallet->balance + $amount;
        $wallet->update(["balance"=>$newBalance]);

        $this->WalletTransaction($amount,Wallet::class,$wallet->id,"charge");

        (new AddNotificationService())->StoreAfterPayment($user,"Your Wallet is Charged by",$amount." is Added to your walletBalance");

        return true;
    }

    public function WalletTransaction($amount,$model,$id,$type){
        Transaction::create([
            "amount"=>$amount,
            "transable_id"=>$id,
            "transable_type"=>$model,
            "type"=>$type
        ]);
    }

    public function createWallet($id){
        Wallet::create(["user_id"=>$id,"balance"=>0]);
        return true;
    }

    public function getWalletData($id){
        $wallet =  Wallet::with("Transaction")->where("user_id",$id)->first();

        if ($wallet) {
            $response = new ApiResponse(200, __('api.your Wallet data retrieved successfully'), ['wallet' => new WalletResource($wallet)]);
            return $response->send();
        }

        $response = new ApiResponse(400, __('api.not have wallet make it first '), ['wallet' => []]);
        return $response->send();
    }
    public function getWalletDataCustomize($id){
        $wallet =  Wallet::with("Transaction")->where("user_id",$id)->first();
        return $wallet;
    }

    public function GetWalletBalance($id){
        $wallet =  Wallet::where("user_id",$id)->first();

        return $wallet->balance;
    }
    public function GetAuthWalletBalance(){
        $wallet =  Wallet::where("user_id",auth()->id())->first();

        return $wallet->balance;
    }

    public function CheckWalletBalance($id){
        $wallet =  Wallet::with("Transaction")->where("user_id",$id)->first();

        if ($wallet->balance > 0) {
            return true;
        }
        return false;
    }

    public function AddRemainToWallet($money,$user_id){
        $driver_balance = $this->GetAuthWalletBalance();
        if (($driver_balance-$money) >= -500){
            $this->chargeToAnthoerUser($money,$user_id);
            $this->charge(($money*-1));
            return true;
        }
        return false;
    }

    public function payWithWallet($user_id,$price,$order_id){
        $resp = $this->CheckWalletBalance($user_id);
        if ($resp){
            $walletBalance = $this->GetWalletBalance($user_id);
            if ($walletBalance > $price){
                $this->depositFromUser($price,$user_id);
                OrderDetails::where("order_id",$order_id)->update([
                    "userPrice"=>0,
                    "walletPaid"=>$price
                ]);
                return 0;
            }else{
                $this->depositFromUser($walletBalance,$user_id);
                OrderDetails::where("order_id",$order_id)->update([
                    "userPrice"=>$price-$walletBalance,
                    "walletPaid"=>$walletBalance
                ]);
                return $price-$walletBalance;
            }
        }
    }
}
