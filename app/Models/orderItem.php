<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class orderItem extends Model
{
    // This Is Orders Items Payment

    protected $fillable = [
        'order_id',
        'plan_id',
        'store_id',
        'price'
    ];
     public function order():BelongsTo{
     return $this->belongsTo(Order::class);
     }
        public function plans(){
        return $this->belongsToMany(Plan::class,'order_items','order_id')->withPivot('created_at','updated_at');
        }
       
        public function store(){
        return $this->belongsToMany(Store::class,'order_items','store_id')->withPivot('created_at');
        }
}
