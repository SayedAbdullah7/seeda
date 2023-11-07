<?php
   
namespace App\Http\Controllers;
   
//use App\Integrations\payment\factory\stripe;
use Illuminate\Http\Request;
use Validator;
use URL;
use Stripe;
use Session;
use Redirect;
use Input;

class StripePaymentController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function stripe(Request $request)
    {
        return view('stripe',['price'=>100]);
    }
  

    public function stripePost(Request $request)
    {
        $stripeToken= "pk_live_xuC7zxOBZHIG9z9p6tyOOZqG00RaObZVCP";
        $STRIPE_SECRET= "sk_live_cpRfcChiPXqvpbO5PvIFLUr300uSfBJJCs";
        //Stripe::setApiKey($STRIPE_SECRET);
        $stripe = Stripe\Stripe::setApiKey($STRIPE_SECRET);

        Stripe\Charge::create ([
                "amount" => $request->price??100,
                "currency" => "usd",
                "source" => $request->stripeToken,
                "description" => "OzGo payment"
        ]);

        Session::flash('success', 'Payment successful!');
        return response()->json(['status'=>200]);
       // return back();
    }

}