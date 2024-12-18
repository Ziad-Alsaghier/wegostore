<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory,HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'start_date',
        'role',
        'plan_id',
        'requestDemo',
        'expire_date',
        'package',
        'image',
        'code',
        'status',
        'country_id',
        'city_id',
    ];
    protected $appends = ['image_link'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
        ];
    }

     public function generateToken($user){
        $user->token = $user->createToken('personal access token')->plainTextToken;
        return $user;
     }

    public function getImageLinkAttribute(){
        if (!empty($this->attributes['image'])) {
            return url('storage/' . $this->attributes['image']);
        }
    }

    public function plan():BelongsTo{
        return $this->belongsTo(Plan::class);
    }
      public function translations()
      {
      return $this->morphMany(Translations::class, 'translatable');
      }


    public function  userStore():HasMany{
        return $this->hasMany(Store::class,'user_id');
    }
    public function  country():BelongsTo{
        return $this->belongsTo(Country::class,'country_id');
    }
    public function  city():BelongsTo{
        return $this->belongsTo(City::class,'city_id');
    }

    public function UserDemoRequest():HasOne{ // User Have One Demo Website 
       return  $this->hasOne(UserDemoRequest::class,'user_id')->with('activity');
    }

    public function promo_codes(){
        return $this->belongsToMany(PromoCode::class, 'user_promo_code');
    }
}
