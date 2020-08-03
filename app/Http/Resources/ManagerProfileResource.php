<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ManagerProfileResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "farm_size" => $this->farm_size,
            "location" => $this->location,
            "commodities_planted" => $this->commodities_planted,
            "contact_person_name" => $this->contact_person,
            "contact_phone_number" => $this->c_person_phone,
            "contact_email_address" => $this->c_person_email,
            "contact_address" => $this->c_person_address,
            "other_info" => $this->c_person_others,
            "state" => $this->state,
            "lg" => $this->lg
        ];

    }
}
