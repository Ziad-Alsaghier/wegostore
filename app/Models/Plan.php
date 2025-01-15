<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    
    protected $fillable = [
        'name',
        'title',
        'fixed',
        'limet_store',
        'app',
        'image',
        'description',
        'setup_fees',
        'monthly',
        'yearly',
        'quarterly',
        'semi_annual',
        'discount_monthly',
        'discount_quarterly',
        'discount_semi_annual',
        'discount_yearly',
    ];
    protected $appends = ['type', 'welcome_offer'];
    protected $translationsData = [];
    
    public function getTypeAttribute(){
        return 'plan';
    }

    public function getWelcomeOfferAttribute(){
        $welcome_offer = $this->hasOne(WelcomeOffer::class, 'plan_id')->first();
        if (empty($welcome_offer)) {
            return false;
        } 
        else {
            return true;
        }
    }

    public function order(){
        return $this->hasMany(Order::class);
    }

    public function latestOrder(){
        return $this->hasOne(Order::class);
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

      public function setTranslationsData(array $translations)
    {
        $this->translationsData = $translations;
    }

    public function extras (){
        return $this->belongsToMany(Extra::class,'extra_plans','plan_id');
    }
}
