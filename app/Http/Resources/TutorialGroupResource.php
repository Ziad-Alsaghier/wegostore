<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TutorialGroupResource extends JsonResource
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
          'description'=>$this->translations->where('key','description')?->first()->value ?? $this->description ,
          'name'=>$this->translations->where('key','name')?->first()->value ?? $this->title ?? $this->name,
          'tutorials' => TutorialGroupResource::collection($this->whenLoaded('tutorials')),
          "video" => url($this->video) ?? $this->video,
          // 'tutorials'=>$this->tutorials,

          ];
        //  parent::toArray($request);
    }
}
