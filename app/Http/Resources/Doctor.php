<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * DoctorResource
 *
 * @mixin \App\Models\Doctor
 */
class Doctor extends JsonResource
{
    use Traits\BelongsToClinic;
    use Traits\HasAppointments;
    use Traits\HasAvailabilities;

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
            'name' => $this->name,
            'speciality' => $this->speciality,
            'city' => $this->city,
            'email' => $this->email,
            'clinic' => $this->clinicToArray(),
            'recent_appointments' => $this->recentAppointmentsLinksArray(),
            'appointments_path' => route('appointment.index', ['doctor_id' => $this->id]),
            'availabilities_path' => route('availability.index', ['doctor_id' => $this->id]),
            //'appointments' => $this->appointmentsLinksArray(),
            //'availabilities' => $this->availabilitiesLinksArray(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'path' => route('doctor.show', ['id' => $this->id]),
        ];
    }
}
