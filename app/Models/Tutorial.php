<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tutorial extends Model
{    
    protected $fillable = [
        'title',
        'description',
        'video',
        'tutorial_group_id',
    ];

    protected $appends = ['video_link'];

    public function getVideoLinkAttribute(){
        return url('storage/' . $this->attributes['video']);
    }

    public function tutorial_group(){
        return $this->belongsTo(TutorialGroup::class, 'tutorial_group_id');
    }
    public function translations()
    {
    return $this->morphMany(Translations::class, 'translatable');
    }
}
