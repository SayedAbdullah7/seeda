<?php

use App\Models\Admin;
use App\Models\Order;
use App\Models\User;
use App\Response\ApiResponse;
use App\services\mediaServices;
use Illuminate\Support\Facades\Auth;
	use App\Models\Language;
	use Illuminate\Support\Str;


	function createDB($model,array $data)
	{
		return $model::create([...$data,'appKey'=>appKey()]);
	}

	function languages()
	{

		return Language::all();

	}
	function currentLang()
	{

		$lang= languages()->where('name',app()->getLocale())->first();

		if(!$lang)
			return languages()->first();

		return $lang;

	}
	function UniqueRandomXDigits ($Digits,$column=null,$tables=[])
	{
		$Digits=$Digits??5;
		$code= rand(pow(10, $Digits-1), pow(10, $Digits)-1);
		for($i=0;$i<count($tables??[]);$i++){
			$model='\App\Models\\'.$tables[$i];
			if($model::where($column,$code)->count() != 0){
				$code= rand(pow(10, $Digits-1), pow(10, $Digits)-1);
				$i=0;
			}
		}
		return $code;
	}

	function permissionInfo($module)
	{
		$myPermissions=[];
		if (str_contains($module['permissions'],'r')){
			$myPermissions[]=['bg'=>'secondary','input'=>$module['name'].'-read','display'=>__('read')];
		}
		if (str_contains($module['permissions'],'c')){
			$myPermissions[]=['bg'=>'primary','input'=>$module['name'].'-create','display'=>__('create')];
		}
		if (str_contains($module['permissions'],'u')){
			$myPermissions[]=['bg'=>'success','input'=>$module['name'].'-update','display'=>__('update')];
		}
		if (str_contains($module['permissions'],'d')){
			$myPermissions[]=['bg'=>'danger','input'=>$module['name'].'-delete','display'=>__('delete')];
		}
		return $myPermissions;
	}

	function createImage($img,$folderPath)
    {
		if(!$img)return null;
		$folderPath="uploads/{$folderPath}/";
		if (!file_exists( $folderPath))
					mkdir( $folderPath, 0777, true);

        $image_parts = explode(";base64,", $img);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $file = $folderPath . uniqid() . '.'.$image_type;
        file_put_contents($file, $image_base64);
		return $file;
    }

	function uploadPhoto($image,$folderName)
	{
		if(!$image) return ;
		$folderPath="uploads/{$folderName}/";
		if (!file_exists( $folderPath))
					mkdir( $folderPath, 0777, true);
		//   $img_name = str::random(30) .now()->timestamp.'.' . $image->getClientOriginalExtension();//generate new name
			$img_name = str::random(30) .now()->timestamp.'.' . $image->getClientOriginalExtension();//generate new name
			$image->move( public_path('uploads/'.$folderName) , $img_name);//move function accept 2para('destnation','filename')
			return '/uploads/'.$folderName.'/'.$img_name;

	}

	function deleteFile($path){
		File::delete($_SERVER['DOCUMENT_ROOT'].$path); // Value is not URL but directory file path
	}

	function GeneralApiFactory($controllerName,$requestClass)
	{
		app()->make($requestClass);
		$appKey= appKey();
		$controllerPath= "\Modules\\".env($appKey)."\Http\Controllers\Api\\".$controllerName;
		return (new $controllerPath())->index(request());
	}
    function GeneralApiFactoryWithApp($appKey,$controllerName,$requestClass)
	{
		app()->make($requestClass);
		$controllerPath= "\Modules\\".env($appKey)."\Http\Controllers\Api\\".$controllerName;
		return (new $controllerPath())->index(request());
	}

    function GeneralApiOrderFactory($controllerName,$requestClass,$order)
	{
		app()->make($requestClass);
		$appKey= appKey();
		$controllerPath= "\Modules\\".env($appKey)."\Http\Controllers\Api\\".$controllerName;
		return (new $controllerPath())->index(request(),$order);
	}

    function GeneralAdminApiFactory($controllerName,$requestClass,$function,$key)
	{
		app()->make($requestClass);
		$appKey= appKey();
		$controllerPath= "\Modules\\".env($appKey)."\Http\Controllers\Admin\\".$key."\\".$controllerName;
        if ($requestClass == null){
            return (new $controllerPath())->$function();
        }else{
            return (new $controllerPath())->$function(request());
        }
	}

    function SaveOrUpdateMedia($user,$request,$type){

        $userMedias = auth()->user()->medias()->first();
        if ($userMedias == null ){
            mediaServices::StoreMedia($request,$user->id,User::class,env(appKey())."/upload/user/",$type);
        }else{
            mediaServices::update($request,$user->id,User::class,env(appKey())."/upload/user/",$type,$userMedias->id);
        }
    }

    function SaveMedia($user,$request,$type){
        mediaServices::StoreMedia($request,$user->id,User::class,env(appKey())."/upload/user/",$type);
    }
    function SaveMedia1($model,$modelClass,$request,$type){
        mediaServices::StoreMedia($request,$model->id,$modelClass,env(appKey())."/upload/order/",$type);
    }

    function SaveMedia2($model,$modelClass,$request,$name,$type){
        mediaServices::StoreMedia($request,$model->id,$modelClass,env(appKey())."/upload/".$name."/",$type);
    }

	function getModelPath($_id)
	{
		$types=[
			'userId'=>'App\Models\User',
			'user_id'=>'App\Models\User',
			'order_id'=>'App\Models\Order',
			'orderId'=>'App\Models\User',
		];

		return $types[$_id]??'';
	}

	function appKey()
	{
		$key= request()->header('appKey')??request()->appKey;
		if(!$key)
            return (new ApiResponse(403,'appKey required!',[]))->send();

        return $key;
	}
