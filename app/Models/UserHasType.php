<?php

namespace App\Models;

use App\Traits\ActivityLogTraits;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserHasType extends Model
{
    use HasFactory,ActivityLogTraits;
}
