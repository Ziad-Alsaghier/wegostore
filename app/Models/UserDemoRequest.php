<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserDemoRequest extends Model
{
    //
    protected $fillable = [
        'user_id',
        'demo_link',
        'email',
        'password',
        'status',
        'activity_id',
        'start_date',
        'end_date',
    ]; 

 public function activity(){
 return $this->belongsTo(Activity::class);
 }
 public function users (){
 return $this->belongsTo(User::class,'user_id');
 }


  public static function getAllWithDefaultMessage()
    {
        $items = self::all();
        if ($items->isEmpty()) {
            return [
                'message' => 'No data available.'
            ];
        }

        return $items;
    }

}
