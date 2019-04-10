<?php

namespace App\Observers;

use App\Models\Appointment;
use App\Concerns\VerifiesWalkInHours;

class AppointmentObserver
{
    use VerifiesWalkInHours;

    /**
     * Handle the appointment "saving" event.
     *
     * @param  Appointment $appointment
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function saving(Appointment $appointment)
    {
        if ($appointment->status === 'cancelled') {
            $appointment->availabilities()->sync([]);
            $appointment->delete();
        }

        if ($appointment->availabilities()->exists()) {
            if ($appointment->status === 'rescheduled') {
                //TODO: use rescheduled state to notify patient.
                //      set appointment to rescheduled if room, doctor or time changes
                $appointment->status = 'active'; // temporarily avoid rescheduled state
            }

            if ($appointment->start->isFuture() && $appointment->end->isFuture()) {
                $appointment->status = 'active';
            }

            if ($appointment->start->isPast() && $appointment->end->isPast()) {
                $appointment->status = 'complete';
            }
        } elseif ($appointment->status !== 'cancelled' || $appointment->status !== 'complete') {
            $appointment->status = 'unscheduled';
        }

        return true;
    }

    /**
     * Handle the appointment "creating" event.
     *
     * @param  Appointment  $appointment
     *
     * @return void
     *
     * @throws \Exception
     */
    public function creating(Appointment $appointment)
    {
        if ($appointment->type === 'checkup' && $appointment->patient->has_checkup) {
            abort('412', 'Patient #' . $appointment->patient_id . ' already scheduled a checkup this year.');
            //$appointment->delete();
        }
    }

    /**
     * Handle the appointment "created" event.
     *
     * @param  Appointment  $appointment
     *
     * @return void
     *
     * @throws \Exception
     */
    public function created(Appointment $appointment)
    {
        //
    }

    /**
     * Handle the appointment "updated" event.
     *
     * @param  Appointment  $appointment
     * @return void
     */
    public function updated(Appointment $appointment)
    {
        //
    }

    /**
     * Handle the appointment "deleted" event.
     *
     * @param  Appointment  $appointment
     * @return void
     */
    public function deleted(Appointment $appointment)
    {
        //
    }

    /**
     * Handle the appointment "restored" event.
     *
     * @param  Appointment  $appointment
     * @return void
     */
    public function restored(Appointment $appointment)
    {
        //
    }

    /**
     * Handle the appointment "force deleted" event.
     *
     * @param  Appointment  $appointment
     * @return void
     */
    public function forceDeleted(Appointment $appointment)
    {
        //
    }
}
