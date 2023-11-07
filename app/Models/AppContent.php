<?php

namespace App\Models;

use App\Traits\ActivityLogTraits;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppContent extends Model
{
    use HasFactory,ActivityLogTraits;

    protected $table="app_content";

    protected $fillable =[
        "key",
        "content",
        "local",
        "appKey",
        "user_id",
    ];
}
