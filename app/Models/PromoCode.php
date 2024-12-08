<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoCode extends Model
{
    //
    protected $fillable = [
        'code',
        'title',
        'promo_status',
        'calculation_method',
        'usage',
        'user_usage',
        'user_type',
        'start_date',
        'end_date',
        'amount', 
        'quarterly',
        'semi_annual',
        'yearly',
        'monthly',
        'promo_type',
    ];

    public function users(){
        return $this->belongsToMany(User::class, 'user_promo_code');
    }

    public function promo_types(){
        $this->hasMany(PromoCodeService::class,'promo_code_id');
    }
}
