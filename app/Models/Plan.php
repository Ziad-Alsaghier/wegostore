<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    //
    protected $table = 'plans';
    protected $fillable = [
        'name',
        'title',
        'fixed',
        'limet_store',
        'app',
        'image',
        'description',
        'setup_fees',
        'price_per_month',
        'price_per_year'
    ];


     public function orderItems()
    {
        return $this->belongsToMany(
            OrderItem::class,        // Related model
            'order_items',           // Pivot table name
            'plan_id',               // Foreign key on the pivot table for the current model (Plan)
            'store_id'               // Foreign key on the pivot table for the related model (OrderItem)
        );
    }
}
