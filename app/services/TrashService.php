<?php

namespace App\services;

class TrashService
{
    public static function moveToTrah($model){
        $model->delete();
    }
}