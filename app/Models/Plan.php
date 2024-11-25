<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $table = 'plans';
    protected $fillable = [
        'name',
        'title',
        'fixed',
        'limet_store',
        'app',
        'image',
        'description',
        'setup_fees',
        'monthly',
        'yearly',
        'quarterly',
        'semi-annual',
        'discount_monthly',
        'discount_quarterly',
        'discount_semi_annual',
        'discount_yearly',
    ];
    protected $appends = ['type'];

    public function getTypeAttribute(){
        return 'plan';
    }

    public function order(){
        return $this->hasMany(Order::class);
    }
    public function latestOrder(){
        return $this->hasOne(Order::class);
    }
}
