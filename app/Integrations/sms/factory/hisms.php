<?php 

namespace App\Integrations\sms\factory;

use Illuminate\Support\Facades\Http;
use App\Repository\interfaces\sms;

class hisms implements sms
{
	public string $phone;
	public int $otp;
	private string $url = 'https://www.hisms.ws/api.php';

	function __construct(string $phone, string $otp)
	{
		$this->phone= $phone;
		$this->otp= $otp;
		$this->sendSms();
	}
	
	public function setData():array
	{
		return [
			'send_sms' => '1',
            'username' => env('usernamesms'),
            'password' => env('passwordsms'),
            'sender' => env('sendersms'),
            'numbers' => $this->phone,
            'message'=>$this->otp,
		];
	}

	public function sendSms() :void
	{
		$response = Http::get($this->url, $this->setData());
	}
}
