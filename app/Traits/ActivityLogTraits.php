<?php

namespace App\Traits;

use App\services\ActivityLog\ActivityLogObserve;

trait ActivityLogTraits
{
    public static function bootActivityLogTraits()
    {
        static::observe(new ActivityLogObserve());
    }
}