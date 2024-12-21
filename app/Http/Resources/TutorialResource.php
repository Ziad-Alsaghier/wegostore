<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TutorialResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
             'name'=>$this->translations->where('key','name')?->first()->value ?? $this->title,
             'title'=>$this->translations->where('key','title')?->first()->value ?? $this->title,
             'description'=>$this->translations->where('key','description')?->first()->value ?? $this->description,
        ];
    }
}
