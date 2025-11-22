<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProviderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'type' => $this->type,
            'status' => $this->status,
            'rating' => $this->rating,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            // Include profile if loaded
            'profile' => $this->whenLoaded('profile'),
            // Include specialties if loaded
            'specialties' => $this->whenLoaded('specialties'),
            // Include availability if loaded
            'availability' => $this->whenLoaded('availability'),
        ];
    }
}