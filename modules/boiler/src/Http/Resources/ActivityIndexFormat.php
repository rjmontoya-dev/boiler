<?php

namespace Boiler\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityIndexFormat extends JsonResource
{
       /**
     * Transform the resource into an array.
     *
     * @param Request $request
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'subject_name' => $this->subject_name,
            'event' => $this->event,
            'description' => $this->description,
            'causer_name' => $this->causer_name,
            'created_at' => $this->created_at,
        ];
    }
}
