<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * AvailabilityResource
 *
 * @mixin \App\Availability
 */
class Availability extends JsonResource
{
    use Traits\HasDoctor;

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
            'start' => $this->start,
            'end' => $this->end,
            'is_available' => $this->is_available,
            'reason_of_unavailability' => $this->reason_of_unavailability ?? 'Doctor is available at this time',
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'path' => route('availability.show', ['id' => $this->id]),
        ];
    }
}
