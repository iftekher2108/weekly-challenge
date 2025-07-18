<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $guarded = [];

    public function user() {
        return $this->belongsTo(Profile::class,'user_id','id');
    }

}
