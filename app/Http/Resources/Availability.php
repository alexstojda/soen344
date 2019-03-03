<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Availability extends JsonResource
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
            'id' => $this->id,
            'doctor_id' => $this->doctor_id,
            'doctor' => [
                'id' => $this->doctor_id,
                'name' => '',
                'path' => '',
                'filter_by_doctor_path' => '',
            ],
            'start' => $this->start,
            'end' => $this->end,
            'is_available' => $this->is_available,
            'reason_of_unavailability' => $this->reason_of_unavailability ?? 'Doctor is available at this time',
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
