<?php 

namespace App\Integrations\sms\factory;

use Illuminate\Support\Facades\Http;
use App\Repository\interfaces\sms;

class smssmartegypt implements sms
{
	public string $phone;
	public int $otp;
	private string $url = 'https://smssmartegypt.com/sms/api';

	function __construct(string $phone, string $otp)
	{
		$this->phone= $phone;
		$this->otp= $otp;
		$this->sendSms();
	}
	public function setData():array
	{
		return [
			'username'=>env('usernamesms'),
			'password'=>env('passwordsms'),
			'sendername'=>'Shobeek',
			'mobiles'=>$this->phone,
			'message'=>$this->otp,
		];
	}

	public function sendSms() :void
	{
		$response = Http::get($this->url, $this->setData());
	}
}
