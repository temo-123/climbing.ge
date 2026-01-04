<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TourReservationResource extends JsonResource
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
            'check_in' => $this->check_in,
            'persons' => $this->persons,
            'email' => $this->email,
            'phone' => $this->phone,
            'country' => $this->country,
            'city' => $this->city,
            'text' => $this->text,
            'tour_id' => $this->tour_id,
            'verificate' => $this->verificate ?? false,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'tour' => $this->whenLoaded('tour'),
            'users' => $this->whenLoaded('users'),
        ];
    }
}
