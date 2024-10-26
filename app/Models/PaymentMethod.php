<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class PaymentMethod extends Model
{
    // 
    protected $fillable = [
        'name',
        'description',
        'thumbnail',
        'status',
    ];
}
