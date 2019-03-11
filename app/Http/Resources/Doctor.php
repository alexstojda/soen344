<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * DoctorResource
 *
 * @mixin \App\Doctor
 */
class Doctor extends JsonResource
{
    use Traits\HasAppointments;

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
            'permit_id' => $this->permit_id,
            'name' => $this->full_name,
            'speciality' => $this->speciality,
            'city' => $this->city,
            'email' => $this->email,
            'appointments' => $this->appointmentsLinksArray(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'path' => route('doctor.show', ['id' => $this->id]),
        ];
    }
}
