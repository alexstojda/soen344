<?php

namespace App\Http\Resources\Traits;

use App\Availability;
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
                'is_available' => $availability->is_available,
                'message' => $this->reason_of_unavailability ??
                    __('Doctor is '. ($availability->is_available ? 'available' : 'unavailable') .' at this time'),
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
