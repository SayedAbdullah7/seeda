<?php 

namespace App\Integrations\sms;

use App\Models\SmsAuth;
use App\Repository\interfaces\sms;
use phpDocumentor\Reflection\Types\Boolean;

use function PHPUnit\Framework\returnSelf;

class facrotyMethodSms 
{
	public static function sms(string $phone, string $otp): sms|bool
	{
		$SmsAuth= SmsAuth::where('appKey',request()->header('appKey'))->first();
		if(!$SmsAuth) return false;

		$class= "\App\Integrations\sms\\factory\\".$SmsAuth->company;
        if(class_exists($class))
		    return new $class($phone,$otp,$SmsAuth);
	}
}
