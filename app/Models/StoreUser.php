<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreUser extends Model
{
    protected $fillable = [
        'user_id',
       'store_name',
       'link_store',
       'link_cbanal',
       'email',
       'password',
       'activities_id'
    ];
    //
}
