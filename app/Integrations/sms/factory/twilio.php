<?php 

namespace App\Integrations\sms\factory;

use Illuminate\Support\Facades\Http;
use App\Repository\interfaces\sms;
use Twilio\Rest\Client;

class twilio implements sms
{

	function __construct(public string $phone,public string $otp,public $SmsAuth)
	{
		$this->sendSms();
	}
	
	public function setData():array
	{
        $data= json_decode($this->SmsAuth->data,true);
		return [
			'Account SID' => $data['Account SID']??'',
			'Auth Token' => $data['Auth Token']??'',
			'phone' => $data['phone']??'',
		];
	}

	public function sendSms() :void
	{
		$sid = $this->setData()["Account SID"];
		$token = $this->setData()["Auth Token"];
        $twilio = new Client($sid, $token);

        $twilio->messages->create($this->phone, 
        ['from' =>  $this->setData()["phone"], 'body' => $this->otp] );
      
    }
}
