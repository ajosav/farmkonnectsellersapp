<?php

namespace App\Http\Resources\Profile;

use Illuminate\Http\Resources\Json\JsonResource;

class LogisticProfile extends JsonResource
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
            'driving_license' => $this->driving_license,
            'license_expiration' => $this->license_expiration,
            'registration_no' => $this->vehicle_reg_no,
            'chasis_no' => $this->chasis,
            'uuid' => $this->uuid,
            'other_fields' => json_decode($this->valid_vehicle_doc)
        ];
    }
}
