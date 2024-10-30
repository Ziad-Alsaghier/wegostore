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
       'email',
       'password',
       'activities_id'
       ];
}
