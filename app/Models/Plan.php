<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    //
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
        'price_per_month',
        'price_per_year'
    ];

}
