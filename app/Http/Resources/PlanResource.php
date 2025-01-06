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
        $semi_annual = 'semi_annual';
        return [
            'id' => $this->id,
            'name' => $this->translations->where('key', 'name')->first()?->value ?? $this->name,
            'fixed' => $this->fixed,
            'limet_store' => $this->limet_store,
            'image' => url($this->image),
            'description' => $this->translations->where('key', 'description')->first()?->value ?? $this->description,
            'setup_fees' => $this->setup_fees,
            'app' => $this->app,
            'yearly' => $this->yearly,
            'monthly' => $this->monthly,
            'quarterly' => $this->quarterly,
            'semi_annual' => $this->semi_annual,
            'discount_monthly' => $this->discount_monthly,
            'discount_quarterly' => $this->discount_quarterly,
            'discount_semi_annual' => $this->discount_semi_annual,
            'discount_yearly' => $this->discount_yearly,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'extras' => ExtraResource::collection($this->whenLoaded('extras')),
            'my_plan' => $this->my_plan,
            'package' => $this->package,
            'type' => $this->type,
        ];
    }
}
