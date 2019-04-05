<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * AvailabilityResource
 *
 * @mixin \App\Models\Availability
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
            'id' =>  $this->ids ?? $this->id,
            'doctor' => $this->doctorToArray(),
            'start' => $this->start,
            'end' => $this->end,
            'is_working' => $this->is_working,
            'is_booked' => $this->is_booked,
            'is_available' => $this->is_available,
            'message' => $this->message ??
                __('Doctor is '. ($this->is_working ? 'available' : 'unavailable') .' at this time'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'path' => $this->getPath(),
        ];
    }

    public function getPath()
    {
        if (is_array($this->ids)) {
            return collect($this->ids)->map(function ($id) {
                return route('availability.show', ['id' => $id]);
            })->toArray();
        }
        return route('availability.show', ['id' => $this->id]);
    }
}
