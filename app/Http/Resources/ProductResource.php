<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            "category" => $this->category,
            "code" => $this->code,
            "description" => $this->description,
            "end_date" => $this->end_date,
            "start_date" => $this->start_date,
            "image" => explode(',', $this->image),
            "name" => $this->name,
            "contact_address" => $this->c_person_address,
            "other_info" => $this->c_person_others,
            "state" => $this->state,
            "lg" => $this->lg
        ];

    }
}
