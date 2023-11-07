<?php 

namespace App\Integrations\sms\factory;

use Illuminate\Support\Facades\Http;
use App\Repository\interfaces\sms;

class smscountry implements sms
{
	public string $phone;
	public int $otp;
	public string $url = 'https://api.smscountry.com/SMSCwebservice_bulk.aspx';

	function __construct(string $phone, string $otp)
	{
		$this->phone= $phone;
		$this->otp= $otp;
		$this->sendSms();
	}
	public function setData():array
	{
		return [
			'User'=>env('usernamesms'),
			'passwd'=>env('passwordsms'),
			'sid'=>env('sendersms'),
			'mobilenumber'=>$this->phone,
			'message'=>$this->otp,
			'mtype'=>'N',
			'DR'=>'Y',
		];
	}

	public function sendSms() :void
	{
		$response = Http::get($this->url, $this->setData());
	}
}
