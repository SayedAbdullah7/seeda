<?php

namespace App\Integrations\socialLogin;

use App\Models\Configration;
use Illuminate\Support\Facades\Artisan;
use Laravel\Socialite\Facades\Socialite;

class socialLoginFactory
{
    public static function Login($token,$provider){
        socialLoginFactory::storeEnvData($provider);
        return Socialite::driver($provider)->userFromToken($token);
    }

    public static function storeEnvData($provider){
        //will read variable add be in app configuration and apply it in the config

        $arrayWithNewSettings = Configration::where("key",'LIKE', "%{$provider}%")->where("appKey",appKey())->pluck('value', 'key')->toArray();

        config(['services.'.$provider => $arrayWithNewSettings[$provider] ]);
    }

}