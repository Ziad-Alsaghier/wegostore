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
        $semi_annual = 'semi-annual';
        return [
            'name'=>$this->translations->where('key', 'name')->first()?->value,
            'price'=>$this->price,
            'description'=>$this->translations->where('key', 'description')->first()?->value,
            'status'=>$this->status,
            'yearly'=>$this->yearly,
            'setup_fees'=>$this->setup_fees,
            'monthly'=>$this->monthly,
            'quarterly'=>$this->quarterly,
            'semi-annual'=>$this->$semi_annual,
            'discount_monthly'=>$this->discount_monthly,
            'discount_quarterly'=>$this->discount_quarterly,
            'discount_semi_annual'=>$this->discount_semi_annual,
            'discount_yearly'=>$this->discount_yearly,
        ];

        
    }
}