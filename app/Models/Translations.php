<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Translations extends Model
{
    // T
   
     protected $fillable = ['locale', 'key', 'value'];

     public function translatable()
     {
        return $this->morphTo();
     }
}
