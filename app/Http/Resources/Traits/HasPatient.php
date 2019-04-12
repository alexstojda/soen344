<?php

namespace App\Http\Resources\Traits;

use App\Models\User;

/**
 * Trait HasDoctor
 *
 * @property      int  $patient_id
 * @property-read User $patient
 */
trait HasPatient
{

    /**
     * @return array
     */
    private function patientToArray(): array
    {
        return [
            'id' => $this->patient_id,
            'name' => $this->patient->name,
            'path' => route('patient.show', ['id' => $this->patient_id]),
            'filter_by_patient' => request()->fullUrlWithQuery(['patient_id' => $this->patient_id]),
        ];
    }
}