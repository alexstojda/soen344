<?php

namespace App\Http\Resources\Traits;

use App\Models\Doctor;
use Illuminate\Database\Eloquent\Collection;

/**
 * Trait HasDoctor
 *
 * @property      int      $doctor_id
 * @property-read Doctor   $doctor
 * @property-read Collection|Doctor[] $doctors
 */
trait HasDoctor
{

    /**
     * @param Doctor|null $doctor
     *
     * @return array
     */
    private function doctorToArray(Doctor $doctor = null): array
    {
        $doctor = $doctor ?? $this->doctor;

        return [
            'id' => $doctor->id,
            'name' => $doctor->name,
            'clinic' => [
                'id' => $doctor->clinic->id,
                'name' => $doctor->clinic->name,
                'address' => $doctor->clinic->address,
                'path' => route('clinic.show', ['id' => $doctor->clinic->id]),
            ],
            'path' => route('doctor.show', ['id' => $doctor->id]),
            'filter_by_doctor' => request()->fullUrlWithQuery(['doctor_id' => $doctor->id]),
        ];
    }

    /**
     * @return array
     */
    private function doctorsToArray(): array
    {
        return $this->doctors->map(function (Doctor $doctor) {
            return $this->doctorToArray($doctor);
        })->toArray();
    }
}
