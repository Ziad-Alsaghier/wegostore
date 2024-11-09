<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TutorialGroup extends Model
{
    protected $fillable = [
        'name',
    ];

    public function tutorials(){
        return $this->hasMany(Tutorial::class, 'tutorial_group_id');
    }
}
