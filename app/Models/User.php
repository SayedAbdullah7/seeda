<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Response\ApiResponse;
use App\Traits\ActivityLogTraits;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\NewAccessToken;
use Illuminate\Support\Str;
use App\Enums\orderStatus;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,ActivityLogTraits,SoftDeletes;

    public $guarded=[];
    public $appends=['rate','rateAsDriver'];
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function locations()
    {
        return $this->morphMany(Location::class, 'locationable');
    }

    public function Activelocations()
    {
        return $this->morphOne(ActiveLocation::class, 'locationable');
    }

    public function rates()
    {
        return $this->morphMany(Rate::class, 'rateable');
    }

    public function createToken(string $name, array $abilities = ['*'])
    {
        $token = $this->tokens()->create([
            'name' => $name,
            'token' => hash('sha256', $plainTextToken = Str::random(240)),
            'abilities' => $abilities,
        ]);

        return (new NewAccessToken($token, $token->getKey().'|'.$plainTextToken))->plainTextToken;
    }

    function token()
    {
        return $this->tokens() ; 
    }
    
    static function getNearstDrivers(array $notIn)
    {
        return self::whereNotIn('id',$notIn)->get();
    }

    public function scopeGetByPhone($query)
    {
        $phone= request()->phone;
        $key= request()->header('appKey');
        return $query->withTrashed()->where('phone', $phone)
                    ->where('appKey',$key);
    }

    public function scopeCheckDriver($query)
    {
        return $query->where('is_approved',1)->where('is_online',1)
            ->where('is_active',1)->where('is_verified',1)->where("last_activity",'>',now()->subMinutes(15));
    }

    function getRateAttribute()
    {
        return  $this->rates();
    }

    function getRateAsDriverAttribute()
    {
        if (Auth::user()->currentAccessToken()->abilities[0] == "Admin"){
            $orderOfDriversIds= OrderSentToDriver::whereStatus(orderStatus::end)
                ->pluck('order_id');
        }else{
            $orderOfDriversIds= OrderSentToDriver::whereBelongsTo(auth()->user())
                ->whereStatus(orderStatus::end)
                ->pluck('order_id');
        }
        return  Rate::whereHasMorph('rateable',[Order::class],function($q) use ($orderOfDriversIds){
            return $q->whereIn('rateable_id',$orderOfDriversIds);
        })->get();
    }

    public function Order(){
        return $this->hasMany(Order::class,'user_id',"id");
    }

    public function userInRooms(){
        return $this->hasMany(UserRooms::class);
    }

    public function PromoCode()
    {
        return $this->belongsToMany(PromoCode::class);
    }

    public function Vehicles(){
        return $this->belongsTo(Vehicles::class,"id","user_id");
    }

    public function UserVehicles(){
        return $this->hasMany(UserVehicles::class);
    }

    public function UserProvider(){
        return $this->hasOne(providerUser::class,"user_id","id");
    }

    public function medias(){
        return $this->morphMany("App\Models\Medias","mediaable");
    }

    public function Notify()
    {
        return $this->hasMany(Notify::class);
    }

    public function Task(){
        return $this->belongsToMany(Task::class,'user_tasks');
    }

}
