<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Payment extends Model
{
    protected $fillable = [
        'user_id',
        'payment_method_id',
        'transaction_id',
        'description',
        'invoice_image',
        'status'
    ];
    public function payment_method():BelongsTo{ // This Relation One To Many PaymentMethod has Many Payments
        return $this->belongsTo(PaymentMethod::class,'payment_method_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
    public function order_payment():HasOne{
        return $this->hasOne(Order::class,'payment_id');
    }
    //
}
