<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Extra extends Model
{
    protected $fillable = [
        'name',
        'price',
        'description',
        'status',
        'yearly',
        'setup_fees',
        'monthly',
        'quarterly',
        'semi-annual',
        'discount_monthly',
        'discount_quarterly',
        'discount_semi_annual',
        'discount_yearly',
    ];
    protected $appends = ['type'];

    public function getTypeAttribute(){
        return 'extra';
    }

    public function orders(){
        return $this->hasMany(Order::class,);
    }

}
