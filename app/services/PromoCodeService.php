<?php

namespace App\services;

use App\Models\PromoCode;
use App\Models\PromoCodesUser;
use function PHPUnit\Framework\isEmpty;

class PromoCodeService
{
    static function AddPromoToUser($code){
        $promoCode = PromoCode::where("code",$code)->where("appKey",appKey())->first();

        if ($promoCode){
            $valid =self::validPromo($promoCode);

            if ($valid){
                $promoCodeUser =PromoCodesUser::where("user_id",auth()->id())->where("promo_codes_id",$promoCode->id)->first();
                if ($promoCodeUser){
                    return 3;
                }
                PromoCodesUser::create([
                    "user_id"=>auth()->id(),
                    "promo_codes_id"=>$promoCode->id,
                    "num_of_apply"=>0,
                    "status"=>0,
                ]);
                return 1;
            }
            return 0;
        }
        return 2;
    }

    static function ApplyPromoCodeEnd($amount,$user_id){
        $userPromoCodes = PromoCodesUser::with("PromoCode")->where("user_id", $user_id)->where("status", 0)->get();
        $outPromo = [];
        foreach ($userPromoCodes as $userPromoCode) {
            $check = self::uerValidPromo($userPromoCode);

            if ($check) {
                $new_amount = self::callDiscount($userPromoCode->promo_codes_id, $amount);

                if ($new_amount >= 0 ) {
                    $outPromo[] = [
                        "id" => $userPromoCode->id,
                        "amount" => $new_amount,
                    ];
                }
            }
        }

        if (!empty($outPromo)){
            $outPromo = collect($outPromo)->sortBy('amount')->reverse()->toArray();
            self::updateApplay($outPromo[0]["id"]);
            return $outPromo[0]["amount"];
        }
        return 0;
    }

    static function ApplyPromoCodeStart($amount)
    {
        $userPromoCodes = PromoCodesUser::with("PromoCode")->where("user_id", auth()->id())->where("status", 0)->get();
        $outPromo = [];
        foreach ($userPromoCodes as $userPromoCode) {
            $check = self::uerValidPromo($userPromoCode);

            if ($check) {
                $new_amount = self::callDiscount($userPromoCode->promo_codes_id, $amount);

                if ($new_amount >= 0 ) {
                    $outPromo[] = [
                        "id" => $userPromoCode->id,
                        "amount" => $new_amount,
                    ];
                }
            }
        }

        if (!empty($outPromo)){
            $outPromo = collect($outPromo)->sortBy('amount')->reverse()->toArray();
            return $outPromo[0]["amount"];
        }
        return 0;
    }

    static function callDiscount($id,$amount){
        $PromoCode = PromoCode::where("id",$id)->where("appKey",appKey())->first();
        $newAmount =0 ;
        if ($PromoCode){
            if ($PromoCode->expire_at > now()->toDateString() && $PromoCode->start_at <= now()->toDateString()){

                if ($amount > $PromoCode->min_amount){
                    if ($PromoCode->type ==1 ){
                        $newAmount = $amount - $PromoCode->discount;
                    }else if ($PromoCode->type == 2 ){
                        if ($amount > $PromoCode->max_amount)
                            $amount  = $PromoCode->max_amount ;
                        $per = $amount/100;
                        $discount = $PromoCode->discount*$per;
                        $newAmount = $amount - $discount;
                    }
                    return $newAmount;
                }
                return -3; // return that amount is too small

            }else{
                return -1;// return that promo not valid
            }
        }
        return -2;// return that promo not founded
    }

    static function storePromoCodeDiscount($code,$user){
        $voucher = PromoCode::where("code",$code)->where("appKey",appKey())->first();
        $voucher->users()->attach($user);
    }

    private static function validPromo($promoCode)
    {
        if($promoCode->start_at < now() && $promoCode->expire_at > now()){
            return true;
        }else{
            return false;
        }
    }

    private static function uerValidPromo($UserPromoCode){
        if($UserPromoCode->PromoCode->start_at < now() && $UserPromoCode->PromoCode->expire_at > now()&&$UserPromoCode->status == 0){
            return true;
        }else{
            return false;
        }
    }

    private static function updateApplay($id)
    {
        $userPromo = PromoCodesUser::with("PromoCode")->find($id);
        if ($userPromo){
            $new_num_of_use = $userPromo->num_of_apply+1;
            if ( $new_num_of_use < $userPromo->PromoCode->num_of_use){
                $data = [
                    "num_of_apply" => $new_num_of_use
                ];
            }else{
                $data = [
                    "num_of_apply" => $new_num_of_use,
                    "status"=>1
                ];
            }
            $userPromo->update($data);
        }
    }
}