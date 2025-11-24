<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
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
            'status' => $this->status,
            'scheduled_at' => $this->scheduled_at,
            'duration' => $this->duration,
            'notes' => $this->notes,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            // Include user if loaded
            'user' => $this->whenLoaded('user'),
            // Include provider if loaded
            'provider' => $this->whenLoaded('provider'),
            // Include payment if loaded
            'payment' => $this->whenLoaded('payment'),
        ];
    }
}