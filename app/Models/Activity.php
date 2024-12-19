<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; 

class Activity extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 
    ];
 public function setTranslationsData(array $translations)
 {
 $this->translationsData = $translations;
 }
    public function translations()
    {
    return $this->morphMany(Translations::class, 'translatable');
    }

       public function scopeWithLocale($query, $locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        return $query->with(['translations' => function ($query) use ($locale) {
            $query->where('locale', $locale);
        }]);
    }
   
}
