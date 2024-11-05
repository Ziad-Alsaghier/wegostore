<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\Store;

class Domain extends Model
{
    protected $fillable = [
        'name',
        'user_id',
        'store_id',
        'status',
        'price',
        'price_status',
        'renewdate',
        'rejected_reason',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function store(){
        return $this->belongsTo(Store::class, 'store_id');
    }
}
