<?php

namespace App\Models;

use App\Traits\ActivityLogTraits;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medias extends Model
{
    use HasFactory,ActivityLogTraits;

    protected $table="medias";

    protected $fillable=[
        "mediaable_type",
        "mediaable_id",
        "filename",
        "filetype",
        "type",
        "Second_type",
        "createBy_id",
        "createBy_type",
        "updateBy_id",
        "updateBy_type",
    ];

    public function mediaable()
    {
        return $this->morphTo();
    }
}
