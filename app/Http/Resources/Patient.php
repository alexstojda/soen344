<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * DoctorResource
 *
 * @mixin \App\User
 */
class Patient extends JsonResource
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
            'email' => $this->email,
            'appointments' => $this->appointmentsLinksArray(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'updated_at' => $this->has_annual_checkup,
            'path' => route('patient.show', ['id' => $this->id]),
        ];
    }
}
