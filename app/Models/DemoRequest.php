<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DemoRequest extends Model
{
    //
       protected $fillable = [
       'user_id',
       'demo_link',
       'email',
       'password',
       'status',
       'activity_id',
       ];



       public function activity(){
        $this->belongsTo(Activity::class,'activity_id');
       }
}
