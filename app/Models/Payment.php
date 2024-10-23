<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
    public function order():BelongsTo{
        return $this->belongsTo(Order::class,'payment_id');
    }
    //
}
