<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * DoctorResource
 *
 * @mixin \App\Models\Nurse
 */
class Nurse extends JsonResource
{

    use Traits\BelongsToClinic;

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
            'access_id' => $this->access_id,
            'name' => $this->name,
            'email' => $this->email,
            'clinic' => $this->clinicToArray(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'path' => route('nurse.show', ['id' => $this->id]),
        ];
    }
}
