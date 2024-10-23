<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDemoRequest extends Model
{
    //
    protected $fillable = [
        'user_id',
        'demo_link',
        'email',
        'password',
        'status',
    ]; 
}
