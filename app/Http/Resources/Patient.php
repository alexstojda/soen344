<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * DoctorResource
 *
 * @mixin \App\Models\User
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
            'recent_appointments' => $this->appointmentsRecentLinksArray(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'has_checkup' => $this->has_checkup,
            'path' => route('patient.show', ['id' => $this->id]),
        ];
    }
}
