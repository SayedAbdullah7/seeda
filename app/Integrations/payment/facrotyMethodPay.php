<?php 

namespace App\Integrations\sms;

use Illuminate\Support\Facades\Http;
use App\Repository\interfaces\sms;

class facrotyMethodPay 
{
	public static function pay(string $sms,string $phone, string $otp): sms
	{
        $class= "\App\Integrations\payment\\factory\\".$sms;
        if(class_exists($class))
		    return new $class($phone,$otp);
	}
}
