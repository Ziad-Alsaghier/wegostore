<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    // This About All Websites
    protected $fillable = [
        'user_id',
        'store_name',
        'link_store',
        'link_cbanal',
        'instgram_link',
        'facebook_link',
        'phone',
        'email',
        'password',
        'activities_id',
        'plan_id',
        'status',
        'logo',
    ];
}
