<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoCodeType extends Model
{
    // This Is About Promo Code Type

    protected $fillable = ['promo_code_id','quarterly','semi-annual','yearly','monthly','service'];
}
