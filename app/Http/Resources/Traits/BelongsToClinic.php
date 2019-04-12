<?php

namespace App\Http\Resources\Traits;

use App\Models\Clinic;

/**
 * Trait BelongsToClinic
 *
 * @property      int    $clinic_id
 * @property-read Clinic $room
 */
trait BelongsToClinic
{

    /**
     * @return array
     */
    private function clinicToArray(): array
    {
        return [
            'id' => $this->clinic_id,
            'path' => route('clinic.show', ['id' => $this->clinic_id]),
            'filter_by_clinic' => request()->fullUrlWithQuery(['clinic_id' => $this->clinic_id]),
        ];
    }
}