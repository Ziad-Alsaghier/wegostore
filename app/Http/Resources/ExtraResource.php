<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExtraResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $semi_annual = 'semi_annual';
        return [
            'id'=> $this->id,
            'name'=> $this->translations->where('key', 'name')->first()?->value  ?? $this->name,
            'price'=>$this->price,
            'description'=>$this->translations->where('key', 'description')->first()?->value ?? $this->description,
            'status'=>$this->translations->where('key', 'status')->first()?->value ?? $this->status,
            'yearly'=>$this->yearly,
            'image'=>$this->image,
            'setup_fees'=>$this->setup_fees,
            'monthly'=>$this->monthly,
            'quarterly'=>$this->quarterly,
            'semi_annual'=>$this->$semi_annual,
            'discount_monthly'=>$this->discount_monthly,
            'discount_quarterly'=>$this->discount_quarterly,
            'discount_semi_annual'=>$this->discount_semi_annual,
            'discount_yearly'=>$this->discount_yearly,
            'included'=>$this->included,
            'my_extra'=>$this->my_extra,
            'order_status'=>$this->order_status,
            'type' => $this->type,
        ];

        
    }
}
