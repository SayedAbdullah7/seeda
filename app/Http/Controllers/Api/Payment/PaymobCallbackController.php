<?php

namespace App\Http\Controllers\Api\Payment;

use App\EnumStatus\paymentStatus;
use App\Http\Controllers\Controller;
use App\Integrations\payment\factory\Paymob;
use App\Jobs\RefundPaymobMoney;
use App\Models\Card;
use App\Models\Order;
use App\Models\PaymentTranscation;
use App\Models\PriceConfig;
use App\Models\User;
use App\Models\VehicleReservation;
use App\Models\Vehicles;
use App\Response\ApiResponse;
use App\services\Gps\sdnService;
use App\services\HttpService;
use App\services\order\OrderDetailsService;
use App\services\walletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Modules\Sonic\Transformers\OrderResource;

class PaymobCallbackController extends Controller
{
    private walletService $walletService;

    public function __construct()
    {
        $this->walletService = new walletService();
    }
    public function callback(Request $request){
        $data = $request->all();

        $get_order_user= $this->get_order_user($data);

        $user = $get_order_user["user"];
        $order = $get_order_user["order"];

        $success =filter_var($data['success'], FILTER_VALIDATE_BOOLEAN);

        // $order is transaction information in our system database
        if ($success){
            if ($order->is_wallet){
                if ($order->is_save){
                    $order->update(["status"=>paymentStatus::success]);
                    $card = Card::Where('cardable_id',$user->id)->latest()->first();
                    RefundPaymobMoney::dispatch($data["id"],$data["amount_cents"])->delay(now()->addMinutes(1));
                    return (new ApiResponse(200,__("api.walletChargedSuccessfully"),[
                        "card_id"=>$card->id
                    ]))->send();
                }
                if ($order->status == paymentStatus::pending){
                    $this->walletService->chargeAnthoerUser($order->amount,$order->user_id);
                }
                $order->update(["status"=>paymentStatus::success]);
                return (new ApiResponse(200,__("api.walletChargedSuccessfully"),[]))->send();
            }elseif ($order->is_order){
                $order->update(["status"=>paymentStatus::success]);
                if ($order->order_ended){
                    $userOrder = Order::where("user_id",$order->user_id)->
                    where("appKey","527")->latest()->first();
                    return (new ApiResponse(200,__("api.scooterOrderEndedSuccessfully"),[
                        "data"=>new OrderResource($userOrder),
                        "command"=>Cache::get("order_command_".$userOrder->id),
                        "sdn_token"=>Cache::get("527.sdn.token"),
                        "sdn_user_id"=>Cache::get("527.sdn.userid"),
                        "vehicle_imme"=>Cache::get("order_vehicle_imme_".$userOrder->id),
                    ]))->send();
                }
            }elseif ($order->is_reserve){
                if ($order->status == paymentStatus::pending){
                    Vehicles::find($order->user_order_id)->update(["is_open"=>1]);
                    $vehicle = VehicleReservation::create([
                        "user_id"=>$user->id,
                        "vehicles_id"=>$order->user_order_id,
                        "time"=>$order->time,
                        "active"=>1,
                        "from"=>now(),
                        "to"=>now()->addMinutes($order->time),
                        "appKey"=>527,
                    ]);
                }else{
                    $vehicle = VehicleReservation::where("vehicles_id",$order->user_order_id)->latest()->first();;
                }
                $order->update(["status"=>paymentStatus::success]);

                return (new ApiResponse(200,__("api.ScooterReservedSuccessfully"),["id"=>$vehicle->id]))->send();
            }
        }else{
            if ($order->is_wallet){
                if ($order->is_save){
                    if ($order->status == paymentStatus::pending){
                        $card = Card::Where('cardable_id',$user->id)->latest()->first()->delete();
                    }
                    $order->update(["status"=>paymentStatus::failed]);
                    return (new ApiResponse(406,__("api.faildTosavecard"),[]))->send();
                }
                $order->update(["status"=>paymentStatus::failed]);
                return (new ApiResponse(406,__("api.failedToChargeWallet"),[]))->send();
            }elseif ($order->is_order){
                if ($order->order_ended){
                    $userOrder = Order::where("user_id",$order->user_id)->
                    where("appKey","527")->latest()->first();

                    if ($order->status == paymentStatus::pending){
                        (new walletService())->depositFromUser($order->amount,$order->user_id);
                    }
                    $order->update(["status"=>paymentStatus::failed]);

                    return (new ApiResponse(200,__("api.failToPayAndAddedToWallet"),[
                        "data"=>new OrderResource($userOrder),
                        "command"=>Cache::get("order_command_".$userOrder->id),
                        "sdn_token"=>Cache::get("527.sdn.token"),
                        "sdn_user_id"=>Cache::get("527.sdn.userid"),
                        "vehicle_imme"=>Cache::get("order_vehicle_imme_".$userOrder->id),
                    ]))->send();
                }else{
                    $order->delete();
                    return (new ApiResponse(406,__("api.failedPaid"),[]))->send();
                }
            }elseif ($order->is_reserve){
                $order->update(["status"=>paymentStatus::failed]);
                return (new ApiResponse(406,__("api.failedPaid"),[]))->send();
            }
        }
    }

    public function saveTokenCallbackSonic(Request $request){
        $data = $request->all();
        $user = User::where("email",$data["obj"]["email"])->where("appKey","527")->first();
        $order = PaymentTranscation::where("user_id",$user->id)->latest()->first();
        if ($order->is_save){
            $this->saveCard($user,$user->appKey,$data);
        }
    }

    private function saveCard($user,$appKey, array $data)
    {
        Card::create([
            "cardable_type"=>User::class,
            "cardable_id"=>$user->id,
            "token"=>$data["obj"]["token"],
            "cardDigits"=>$data["obj"]["masked_pan"],
            "appKey"=>$appKey,
        ]);
    }

    private function get_order_user($data)
    {
        $order = PaymentTranscation::where("order_id",$data["order"])->first();
        if ($order){
            $user = User::where("id",$order->user_id)->first();
            return ["order"=>$order,"user"=>$user];
        }else{
            $transaction = (new Paymob(new User(),22,"",""))->getTrancaction($data["id"]);

            $resp = $transaction->json();

            if (!isset($resp['billing_data']["phone_number"]) || !isset($resp['billing_data']["extra_description"]))
                return (new ApiResponse(406,__("api.someUnknownErrorErrorHappenedPleaseConnectUs"),[]))->send();
            $user = User::where("phone",$resp['billing_data']["phone_number"])->where("appKey",$resp['billing_data']["extra_description"])->first();
            $order = PaymentTranscation::where("user_id",$user->id)->latest()->first();

            return ["order"=>$order,"user"=>$user];
        }
    }

}

