<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WelcomeOffer extends Model
{
    protected $fillable = [
        'ar_image',
        'en_image',
        'plan_id',
        'duration',
        'price',
        'status',
    ];
    protected $appends = ['ar_image_link', 'en_image_link', 'type']; 
    public function getTypeAttribute(){
        return 'welcome_offer';
    }

    public function getArImageLinkAttribute(){
        if (!empty($this->attributes['ar_image'])) {
            return url('storage/' . $this->attributes['ar_image']);
        }
    }

    public function getEnImageLinkAttribute(){
        if (!empty($this->attributes['en_image'])) {
            return url('storage/' . $this->attributes['en_image']);
        }
    }

    public function plan(){
        return $this->belongsTo(Plan::class);
    }
}
