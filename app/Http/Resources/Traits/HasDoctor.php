<?php

namespace App\Http\Resources\Traits;

use App\Doctor;

/**
 * Trait HasDoctor
 *
 * @property      int    $doctor_id
 * @property-read Doctor $doctor
 */
trait HasDoctor
{

    /**
     * @return array
     */
    private function doctorToArray(): array
    {
        return [
            'id' => $this->doctor_id,
            'name' => $this->doctor->name,
            'path' => route('doctor.show', ['id' => $this->doctor_id]),
            'filter_by_doctor' => request()->fullUrlWithQuery(['doctor_id' => $this->doctor_id]),
        ];
    }
}