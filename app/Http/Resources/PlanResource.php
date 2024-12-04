<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
             $locale = app()->getLocale(); // Use the application's current locale
                    $semi_annual = 'semi-annual';
        return [
            'id' => $this->id,
            'name' => $this->translations->where('key', 'name')->first()?->value,
            'fixed' => $this->fixed,
            'limet_store' => $this->limet_store,
            'image' => $this->image,
            'description' => $this->translations->where('key', 'description')->first()?->value,
            'setup_fees' => $this->setup_fees,
            'app' => $this->app,
            'yearly' => $this->yearly,
            'quarterly' => $this->quarterly,
            'semi-annual' => $this->annual,
            'discount_monthly' => $this->discount_monthly,
            'discount_quarterly' => $this->discount_quarterly,
            'discount_semi_annual' => $this->discount_semi_annual,
            'discount_yearly' => $this->discount_yearly,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
