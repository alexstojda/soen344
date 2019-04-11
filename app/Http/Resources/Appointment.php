<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * AppointmentResource
 *
 * @mixin \App\Models\Appointment
 */
class Appointment extends JsonResource
{
    use Traits\HasPatient;
    use Traits\HasDoctor;
    use Traits\HasRoom;
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
            'doctor' => $this->doctorToArray(),
            'patient' => $this->patientToArray(),
            'room' => $this->roomToArray(),
            'start' => $this->start,
            'end' => $this->end,
            'type' => $this->type,
            'status' => $this->status,
            'duration' => $this->duration,
            'times_linked' => $this->availabilitiesLinksArray(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'path' => route('appointment.show', ['id' => $this->id]),
        ];
    }
}
