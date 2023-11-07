<?php  

namespace App\Integrations\payment\factory;

use Illuminate\Support\Facades\Http;
use App\Repository\interfaces\payment;
use Stripe as StripPackage;
use Charge;

class stripe 
{
	public string $amount;
	public int $currency;

	function __construct(string $amount, string $currency)
	{
		$this->amount= $amount;
		$this->currency =  $currency;
		$this->pay();
	}
	
	public function setData():array
	{
		return [
			'amount' => $this->amount,
			'currency' => $this->currency,
			'source' => $this->source,
			'description' => $this->description,
		];
	}

	public function pay() 
	{
        StripPackage::setApiKey(env('STRIPE_SECRET'));
        Charge::create ($this->setData());
  
        Session::flash('success', 'Payment successful!');
        return response()->json(['status'=>200]);
	}
}
