<?php

use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\AppContentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('/verifyPhone','Auth\LoginController@sendLoginOtp');
Route::post('/registerBySocial', 'Auth\LoginBySocialController@index');
Route::get('/getAppContent/{key}', [AppContentController::class,'get']);

Route::middleware(['auth:sanctum','appKey'])->group( function () {

    Route::namespace('Auth')->group(function () {
        Route::post('/verifyOtp', 'LoginController@loginOtpValidation');
        Route::post('/register', 'registerController@index');
        Route::post('/verifyPhoneSocial','verfiySocailPhoneController@index');
        Route::post('/verifyOtpSocial', 'verfiySocailOtpController@index');
        Route::post('/resetPassword', 'restPasswordController@index');
        Route::post('/ChangePassword', 'ChangePasswordController@index');
        Route::post('/CheckRegister', 'CheckRegisterController@index');
    });

    Route::get("/vehiclesTypes","VehicleTypeController@getVehicleType");
    Route::get("/vehiclesCompanyTypes","VehicleTypeController@getvehiclesCompanyTypes");

    Route::namespace('Card')->group(function () {
        Route::post("/saveCard", "SaveCardController@index");
        Route::get("/GetMyCard", "GetCardController@index");
        Route::post("/DeleteMyCard", "DeleteCardController@index");
        Route::get("/getLastCard", "GetLastCardController@index");
    });

    Route::middleware(["Token"])->group(function (){

        Route::namespace('Auth')->group(function () {
            Route::get('/GetProfile', 'ProfileController@getProfile');
            Route::get('/getUserById', 'ProfileController@getUserById');
            Route::get('/logout', 'LogoutController@logout');
            Route::post('/updateProfile', 'updateProfileController@index');
            Route::post('/moveToTrash', 'MoveUserToTrashController@index');
            Route::post('/toggleOnline', 'OnlineController@onlineUserToggel');
            Route::put("/updateFcm",'UpdateFcmController@updateFcm');
        });

        Route::get("/UserCars","UsersCarController@index");

        Route::get("/GetNearestCar","GetNearstCarController@index");

        Route::middleware("User")->group(function (){
            Route::namespace('Order')->group(function () {
                Route::post('/getRideType', 'RideTypeController@index');
                Route::post('/AddNewDropOff', 'AddNewDropOffController@index');
                Route::post('/makeOrder', 'makeOrderController@index');
                Route::post('/cancelOrderByUser', 'CancelOrderByUserController@index');
                Route::post('/AcceptOfferByUserController', 'AcceptOfferByUserController@index');
                Route::post('/rateDriverByUser', 'rateDriverByUserController@index');
                Route::post('/DriverOffer', 'SendOfferByDriverController@index');
                Route::post('/applyShardOrder', 'applySheredOrderController@index');
            });

            Route::apiResource('locations', 'LocationController');
            Route::get("getFav",'LocationController@fav');
            Route::get("getLastLocation",'LocationController@getLastPlaces');

            Route::namespace("Promo")->group(function (){
                Route::get("myPromo",'UserPromoCodesController@index');
                Route::post("promo/{code}",'UserAddPromoCodesController@index');
            });

            Route::namespace("Scooter")->group(function (){
                Route::get("GetScooter",'GetScooterController@index');
                Route::post("makeScooterOrder",'OrderScooterController@index');
                Route::post("EndScooterOrder",'EndScooterOrder@index');
                Route::post("getScooterLiveTrack",'getScooterLiveTrackController@index');
                Route::post("VehiclesEndStatus",'VehiclesController@index');
            });

            Route::namespace("Reservation")->group(function (){
                Route::post("VehicleReservation",'VehicleReservationController@index');
                Route::post("VehicleReservationEnd",'VehicleFinishReservationController@index');
            });

            Route::namespace("User")->group(function (){
                Route::get("GetActiveOrder",'GetActiveOrder@index');
            });

        });

        Route::namespace("Station")->group(function (){
           Route::get("getStations","GetStationLocationController@index");
        });

        Route::middleware("Driver")->group(function (){
            Route::namespace('Order')->group(function () {
                Route::post('/acceptOrderByDriver', 'AcceptOrderByDriverController@index');
                Route::post('/arrivedOrderFromByDriver', 'DriverArrivedController@index');
                Route::post('/cancelOrderByDriver', 'CancelOrderByDriverController@index');
                Route::post('/endOrderByDriver', 'EndOrderByDriverController@index');
                Route::post('/startOrderByDriver', 'StartOrderByDriverController@index');
                Route::post('/refuseOrder', 'RefuseOrderController@index');
                Route::post('/sendOffer', 'SendOfferByDriverController@index');
                Route::post('/OrderLiveTracking', 'OrderLiveTracking@index');
            });

            Route::namespace("Earing")->group(function (){
                Route::get("DriverEaring","EaringController@index");
                Route::get("FrontEaring","FrontEaringController@index");
            });

            Route::namespace("Driver")->group(function (){
                Route::get("DriveStatistics","DriveStatisticsController@index");
            });

            Route::namespace('ShardeRideOrder')->group(function () {
                Route::post('/makeShardOrder', 'makeShardRideController@index');
                Route::post('/EndUserShardOrder', 'EndUserShardOrderController@index');
                Route::post('/EndShardOrder', 'EndShardOrderController@index');
                Route::post('/StratShardOrder', 'StratShardOrderController@index');
                Route::post('/CancelShardOrder', 'CancelSharedController@index');
            });

            Route::post("activeLocation","ActiveLocationController@index");
            Route::post("addMoneyToWallet","AddMoneyToWalletAfterPayController@index");

        });

        Route::namespace('Order')->group(function () {
            Route::post('/makeNewOrder', 'orderController@store');
            Route::post('/makeHoursOrder', 'makeHoursOrderController@index');
            Route::post('/getMyOrders', 'GetMyOrdersController@index');
            Route::post('/getMyOrderById', 'ShowOrderController@index');
            Route::get('/LastStatusOrder', 'LastStatusController@index');
            Route::post('/getMyOrders', 'GetMyOrdersController@index');
            Route::get("/historyOrder","historyOrdersController@index");
            Route::post("/changePayment","ChangePaymentController@index");
            Route::post("/orderFlag","UnPlaceOrderController@index");
        });

        Route::namespace('ShardeRideOrder')->group(function () {
            Route::get('/getValidShardOrders', 'getSherdOrderController@index');
            Route::get('/getMyValidShardOrders', 'getMySherdOrderController@index');
        });


        Route::namespace('Payment')->group(function () {
            Route::get("/orderCheckOUt/{id}","CheckOutController@getOrderData");
            Route::get("/getPaymentTypes","PaymentTypeController@index");
            Route::post("/payOrder","PayOrderController@pay");
            Route::post("/isPaid","PayOrderController@isPaid");
        });

        Route::namespace('Rate')->group(function () {
            Route::post('/AddRate', 'AddRateController@index');
            Route::post('/EditRate', 'EditRateController@index');
            Route::delete('/deleteRate', 'deleteRateController@index');
            Route::get('/GetRate', 'GetRateController@index');
            Route::get('/GetMyRate', 'GetMyRateController@index');
        });

        Route::namespace('Wallet')->group(function () {
            Route::get('/getWalletData', 'getWalletController@index');
            Route::post('/chargeMyBalance', 'chargeMyWalletController@index');
            Route::middleware("EmployeeMiddleware")->group(function (){
                Route::post("AddMoneyToWallet","EmployeeAddMoneyController@index");
                Route::get("getEmployeeBalance","GetEmployeeBalanceController@index");
            });
        });

        Route::namespace('Points')->group(function () {
            Route::get('/getUserPointData', 'GetPointsController@index');
        });

        Route::namespace('geofences')->group(function () {
            Route::get('/cityGeofence', 'CityGeofencesController@index');
            Route::get('/checkUserInZone',"checkUserInZoneController@index");
        });

        Route::get("downloadFiles",'downloadFileController@download');
        Route::post("uploadOrderImage",'UploadImageController@uploadOrderImage');
        Route::post("uploadImage",'UploadImageController@uploadUserImage');
        Route::post("uploadDriverImages",'UploadImageController@uploadDriverImages');

        Route::namespace('Chat')->group(function () {
            Route::get('/getRooms', 'ChatController@getRooms');
            Route::get('/getMassages', 'getChatMassageController@index');
            Route::post('/sendMassages', 'ChatController@sendMassage');
        });


        Route::namespace("Subscriptions")->group(function (){
            Route::get("getSubscription","SubscriptionsController@index");
            Route::post("subscriber","SubscriptionsController@Subscriber");
            Route::post("renew","SubscriptionsController@renew");
            Route::post("upgrade","SubscriptionsController@upgrade");
            Route::get("SubscriberCheck","SubscriptionsController@SubscriberCheck");
        });

        Route::controller(NotificationController::class)->group(function () {
            Route::get('/getAllNotification', 'index');
            Route::get('/showNotification/{id}', 'show');
            Route::put('/markAllAsRead', 'markAllAsRead');
            Route::put('/markAsRead/{id}', 'markAsRead');
        });
    });
    Route::get('/CardSave', 'Card\CardSaveController@index');
});



Route::get("/callback","Payment\PaymobCallbackController@callback");
Route::post("/test/callback","Payment\PaymobCallbackController@saveTokenCallbackSonic");

//Route::get('/chargeMyBalance', 'Wallet\chargeMyWalletController@index');
