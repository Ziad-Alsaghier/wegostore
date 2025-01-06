<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        //  parent::toArray($request);
        return [
            "id" => $this->id,
            "name" => $this->name,
            "email" => $this->email,
            "phone" => $this->phone,
            "role" => $this->role,
            "user_id" => $this->user_id,
            "plan_id" => $this->plan_id,
            "expire_date" => $this->expire_date,
            "requestDemo" => $this->requestDemo,
            "email_verified_at" => $this->email_verified,
            "image" => $this->image,
            "start_date" => $this->start_date,
            "package" => $this->package,
            "code" => $this->code,
            "status" => $this->status,
            "country_id" => $this->country,
            "city_id" => $this->city_id,
            "image_link" => $this->image_link,
            "plan" => $this->whenLoaded('plan', value: function () {
                return new PlanResource($this->plan);
            }),
        ];
    }
}
