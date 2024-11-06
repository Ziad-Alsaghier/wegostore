<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Models\Plan;
use App\Models\Payment;
use App\Models\User;
use App\Models\Domain;
use App\Models\Extra;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'payment_id',
        'plan_id',
        'domain_id',
        'extra_id',
    ];

    public function payment():BelongsTo{
      return $this->belongsTo(Payment::class);
    }

    public function plans(){
      return $this->belongsTo(Plan::class,'plan_id');
    }

    public function users():BelongsTo{
      return $this->belongsTo(User::class,'user_id');
    }

    public function domain():BelongsTo{
      return $this->belongsTo(Domain::class,'domain_id');
    }

    public function extra():BelongsTo{
      return $this->belongsTo(Extra::class,'extra_id');
    }
     
}
