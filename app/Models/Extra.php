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
    ];

    public function orders(){
        return $this->hasMany(Order::class,);
    }

}
