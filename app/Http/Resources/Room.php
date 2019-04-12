<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * RoomResource
 *
 * @mixin \App\Models\Room
 */
class Room extends JsonResource
{
    use Traits\BelongsToClinic;
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
            'number' => $this->number,
            'name' => $this->name,
            'clinic' => $this->clinicToArray(),
            'appointments' => $this->appointmentsLinksArray(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'path' => route('room.show', ['id' => $this->id]),
        ];
    }
}
