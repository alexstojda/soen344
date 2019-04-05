<?php

namespace App\Http\Resources\Traits;

use App\Models\Availability;
use Illuminate\Support\Collection;

/**
 * Trait HasAvailabilities
 *
 * @method Availability[]|Availability availabilities()
 */
trait HasAvailabilities
{

    /**
     * @return Collection|Availability[]
     */
    private function availabilitiesToArray()
    {
        return $this->availabilities()->get()->map(function (Availability $availability) {
                return [
                'id'     => $availability->id,
                'start'  => $availability->start,
                'end'    => $availability->end,
                'is_working' => $availability->is_working,
                'message' => $this->message ??
                    __('Doctor is '. ($availability->is_working ? 'available' : 'unavailable') .' at this time'),
                'path'   => route('availability.show', ['id' => $availability->id]),
            ];
            });
    }

    /**
     * @return Collection|Availability[]
     */
    private function availabilitiesLinksArray()
    {
        return $this->availabilities()->available()->get()->map(function (Availability $availability) {
            return [
                'id'     => $availability->id,
                'start'  => $availability->start,
                'end'    => $availability->end,
                'path'   => route('availability.show', ['id' => $availability->id]),
            ];
        });
    }
}
