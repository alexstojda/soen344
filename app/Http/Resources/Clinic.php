<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * ClinicResource
 *
 * @mixin \App\Models\Clinic
 */
class Clinic extends JsonResource
{
    use Traits\HasRoom;
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
            'name' => $this->name,
            'address' => $this->address,
            'phone' => $this->phone,
            'open' => $this->open,
            'close' => $this->close,
            'rooms' => $this->roomsToArray(),
            'doctors' => $this->doctorsToArray(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'path' => route('clinic.show', ['id' => $this->id]),
        ];
    }
}
