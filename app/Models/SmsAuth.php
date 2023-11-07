<?php

namespace App\Models;

use App\Traits\ActivityLogTraits;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsAuth extends Model
{
    use HasFactory,ActivityLogTraits;
    public $table = 'sms_auth';
}
