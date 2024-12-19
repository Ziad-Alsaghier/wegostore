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
             'title'=>$this->translations->where('key','title')?->first()->value ?? $this->title,
             'description'=>$this->transaltions->where('key','description')->first()?->value ?? $this->transaltions,
             'video'=>$this->video,
              "tutorial_group" => $this->tutorial_group,
        ];
    }
}
