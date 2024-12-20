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
     public function scopeWithLocale($query, $locale = null)
     {
     $locale = $locale ?: app()->getLocale();
     return $query->with(['translations' => function ($query) use ($locale) {
     $query->where('locale', $locale);
     }]);
     }
    public function translations()
    {
    return $this->morphMany(Translations::class, 'translatable');
    }
}
