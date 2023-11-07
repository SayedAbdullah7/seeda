<?php 

namespace App\Integrations\sms\factory;

use Illuminate\Support\Facades\Http;
use App\Repository\interfaces\sms;

class sms4ksa implements sms
{
	public string $phone;
	public string $otp;
	public string $url = 'https://www.sms4ksa.com/api/sendsms.php';

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
			'sender'=>'BIRMA',
			'numbers'=>$this->phone,
			'message'=>$this->otp,
			'unicode'=>'e'
		];
	}

	public function sendSms() :void
	{
		$response = Http::get($this->url, $this->setData());
	}
}
