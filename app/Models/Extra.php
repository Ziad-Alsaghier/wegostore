<?php

namespace App\Models;

use App\Observers\ExtraObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Extra extends Model
{
    use HasFactory,HasApiTokens, Notifiable;
       
    protected $fillable = [
        'name',
        'price',
        'description',
        'status',
        'yearly',
        'setup_fees',
        'monthly',
        'quarterly',
        'semi_annual',
        'discount_monthly',
        'discount_quarterly',
        'discount_semi_annual',
        'discount_yearly',
        'included',
    ];
    
    protected $appends = ['type'];
        
    public function getTypeAttribute()
    {
        return 'extra';
    }

    public function orders()
    {
        return $this->hasOne(Order::class,);
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

    public function plan_included(){
      return $this->belongsToMany(Plan::class, 'extra_plans','extra_id','plan_id');
    }
}
