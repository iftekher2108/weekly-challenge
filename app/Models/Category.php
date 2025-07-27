<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = [];


    public function children(){
        return $this->hasMany(Category::class, 'parent_id','id');
    }

    public function parent() {
        return $this->belongsTo(Category::class,'parent_id','id');
    }

    public function task() {
        return $this->hasMany(task::class,'cat_id','id');
    }

    public function company() {
        return $this->belongsTo(\App\Models\Company::class, 'company_id');
    }


}
