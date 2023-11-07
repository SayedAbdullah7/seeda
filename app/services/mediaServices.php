<?php

namespace App\services;

use App\Models\Medias;
use App\Models\User;

class mediaServices
{
    public static function StoreMedia($file,$id,$model,$path,$media){
        $media["filename"]= mediaServices::uploadImage($file,$path);
        $media["mediaable_type"]= $model;
        $media["mediaable_id"]= $id;
        $media["createBy_id"]= auth()->id();
        $media["createBy_type"]= User::class;
        $mediaStored = Medias::create($media);
        return $mediaStored->id;
    }
    public static function StoreMediaData($file,$id,$model,$path,$type){

        $media["filename"]= mediaServices::uploadImage($file,$path);

        $media["type"]= $type;
        $media["filetype"]= "image";
        $media["mediaable_type"]= $model;
        $media["mediaable_id"]= $id;
        $media["createBy_id"]= auth()->id();
        $media["createBy_type"]= User::class;

        $mediaStored = Medias::create($media);

        return $mediaStored->id;
    }

    public static function StoreMediaDataSecond($file,$id,$model,$path,$type,$second){

        $media["filename"]= mediaServices::uploadImage($file,$path);

        $media["Second_type"]= $second;
        $media["type"]= $type;
        $media["filetype"]= "image";
        $media["mediaable_type"]= $model;
        $media["mediaable_id"]= $id;
        $media["createBy_id"]= auth()->id();
        $media["createBy_type"]= User::class;

        $mediaStored = Medias::create($media);

        return $mediaStored->id;
    }

    public static function update($file,$id,$model,$path,$media,$media_id){
        $media["filename"]= mediaServices::uploadImage($file,$path);
        $media["mediaable_type"]= $model;
        $media["mediaable_id"]= $id;
        $media["createBy_id"]= auth()->id();
        $media["createBy_type"]= User::class;
        $mediaStored = Medias::find($media_id);
        $mediaStored->update($media);
        return $mediaStored->id;
    }

    public static function uploadImage($file, $path = '/assets/upload/'){

        $filename= time().'.'.$file->getClientOriginalExtension();
        $file-> move(public_path($path), $filename);
        return $path.$filename;
    }
}
