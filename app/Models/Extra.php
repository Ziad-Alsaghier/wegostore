<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Extra extends Model
{
    protected $fillable = [
        'name',
        'price',
        'description',
        'status',
        'yearly',
        'setup_fees',
        'monthly',
        'quarterly',
        'semi-annual',
        'discount_monthly',
        'discount_quarterly',
        'discount_semi_annual',
        'discount_yearly',
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
}
