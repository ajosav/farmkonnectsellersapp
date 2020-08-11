<?php

namespace App\Http\Resources\ProfileResource;

use Illuminate\Http\Resources\Json\JsonResource;

class RetailerProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'location' => $this->location,
            'other_info' => $this->other_info,
            'state' => $this->state,
            'lg' => $this->lg,
            'uuid' => $this->uuid
        ];
    }
}
