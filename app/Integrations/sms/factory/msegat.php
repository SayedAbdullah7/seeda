<?php 

namespace App\Integrations\sms\factory;

use Illuminate\Support\Facades\Http;
use App\Repository\interfaces\sms;

class msegat implements sms
{
	public string $phone;
	public int $otp;
	private string $url = 'https://www.msegat.com/gw/sendsms.php';

	function __construct(string $phone, string $otp)
	{
		$this->phone= $phone;
		$this->otp= $otp;
		$this->sendSms();
	}
	public function setData():array
	{
		return [
			"apiKey"=>env("apiKeysms"),
			"userName"=>env("userNamesms"),
			"numbers"=>$this->phone,
			"userSender"=>env("userSendersms"),
			"msgEncoding"=>"UTF8",
			"msg"=>$this->otp
		];
	}

	public function sendSms() :void
	{
		$response = Http::post($this->url, $this->setData());
	}
}
