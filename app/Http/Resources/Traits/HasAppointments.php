<?php

namespace App\Http\Resources\Traits;

use App\Appointment;
use Illuminate\Support\Collection;

/**
 * Trait HasAppointments
 *
 * @method Appointment[]|Appointment appointments()
 */
trait HasAppointments
{

    /**
     * @return Collection|Appointment[]
     */
    private function appointmentsToArray()
    {
        return $this->appointments()->whereStatus('active')->get()
            ->map(function (Appointment $appointment) {
                return [
                'id'     => $appointment->id,
                'status' => $appointment->status,
                'type'   => $appointment->type,
                'start'  => $appointment->start,
                'end'    => $appointment->end,
                'path'   => route('appointment.show', ['id' => $appointment->id]),
            ];
            });
    }

    /**
     * @return Collection|Appointment[]
     */
    private function appointmentsLinksArray()
    {
        return $this->appointments()->get()->map(function (Appointment $appointment) {
            return [
                'id'     => $appointment->id,
                'status' => $appointment->status,
                'path'   => route('appointment.show', ['id' => $appointment->id]),
            ];
        });
    }
}
