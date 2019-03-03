<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Appointment extends JsonResource
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
            'patient_id' => $this->patient_id,
            'room_id' => $this->room_id,
            'doctor' => [
                'id' => $this->doctor_id,
                'name' => '',
                'path' => '',
                'filter_by_doctor_path' => '',
            ],
            'patient' => [
                'id' => $this->patient_id,
                'name' => '',
                'path' => '',
                'filter_by_patient_path' => '',
            ],
            'room' => [
                'id' => $this->room_id,
                'name' => '',
                'path' => '',
                'filter_by_room_path' => '',
            ],
            'start' => $this->start,
            'end' => $this->end,
            'type' => $this->type,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
