<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

use App\Models\Order;

class Payment extends Model
{
    protected $fillable = [
        'user_id',
        'amount',
        'discount',
        'payment_method_id',
        'transaction_id',
        'description',
        'invoice_image',
        'status',
    ];
    protected $appends = ['invoice_image_link'];

    public function getInvoiceImageLinkAttribute(){
        return url('storage/' . $this->attributes['invoice_image']);
    }

    public function payment_method():BelongsTo{ // This Relation One To Many PaymentMethod has Many Payments
        return $this->belongsTo(PaymentMethod::class,'payment_method_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    } 

    public function orders():HasMany{
        return $this->hasMany(Order::class,'payment_id');
    }
        public function getExpireDateAttribute($date){
        return $date->format('Y-m-d');
        }

       
    public function extra():HasMany{
        return $this->hasMany(Order::class,'extra_id');
    }
    //
}
