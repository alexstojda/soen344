<?php

namespace App\Http\Resources\Traits;

use App\Models\Appointment;
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

    /**
     * @return Collection|Appointment[]
     */
    private function recentAppointmentsLinksArray()
    {
        $array = [
            'prev' => null,
            'next' => null,
        ];
        if ($this->last_appointment !== null) {
            $array['prev'] = [
                'id'     => $this->last_appointment->id,
                'status' => $this->last_appointment->status,
                'path'   => route('appointment.show', ['id' => $this->last_appointment->id]),
            ];
        }
        if ($this->next_appointment !== null) {
            $array['next'] = [
                'id'     => $this->next_appointment->id,
                'status' => $this->next_appointment->status,
                'path'   => route('appointment.show', ['id' => $this->next_appointment->id]),
            ];
        }
        return $array;
    }
}
