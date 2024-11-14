<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoCode extends Model
{
    //
    protected $fillable = [
        'code',
        'title',
        'calculation_method',
        'usage',
        'user_usage',
        'user_type',
        'start_date',
        'end_date',
        'promo_code_id',
        'quarterly',
        'semi-annual',
        'yearly',
        'monthly',
    ];

    public function users(){
        return $this->belongsToMany(User::class, 'user_promo_code');
    }

    public function promo_types(){
        $this->hasMany(PromoCodeService::class,'promo_code_id');
    }
}
