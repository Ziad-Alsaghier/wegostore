<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'payment_id',
        'total_amount',
        'order_status',
    ];
    //

     public function payment():BelongsTo{
     return $this->belongsTo(Payment::class);
     }
     public function order_items():HasMany{
     return $this->hasMany(orderItem::class);
     }
}
