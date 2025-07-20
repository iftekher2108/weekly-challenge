<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class task extends Model
{
    protected $guarded = [];

    public function user() {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function category() {
        return $this->belongsTo(Category::class,'cat_id','id');
    }

    
    public function scopeOverdueLastWeek($query)
{
    return $query->where('status', 'progress')
        ->whereBetween('due_date', [
            now()->subWeek()->startOfWeek(),
            now()->subWeek()->endOfWeek()
        ]);
}

}
