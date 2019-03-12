<?php

namespace App\Observers;

use App\Appointment;

class AppointmentObserver
{
    use Concerns\VerifiesWalkInHours;

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
        // Check if appointment without walk-in hours
        if(!$this->verifyWalkInHours($appointment->start, $appointment->end)) {
            dump('rejected due to open hours rule | ' .
                $appointment->start->toDateTimeString() .' | '. $appointment->end->toDateTimeString() );
            return false;
        }

        //Check if doctor is available
        if (!$appointment->doctor->is_available_on($appointment->start, $appointment->type)) {
            dump('rejected due to doctor availability rule | ' . "Doc ID: {$appointment->doctor_id} | " .
                $appointment->start->toDateTimeString() .' | '. $appointment->end->toDateTimeString());
            return false;
        }
        //Check if room is available
        if (!$appointment->room->is_available_on($appointment->start, $appointment->type)) {
            dump('rejected due to  room availability rule | ' . "Room ID: {$appointment->room_id} | " .
                $appointment->start->toDateTimeString() .' | '. $appointment->end->toDateTimeString());
            return false;
        }

        //TODO: use rescheduled state to notify patient.
        //      set appointment to rescheduled if room, doctor or time changes
        if($appointment->status === 'rescheduled') {
            $appointment->status = 'active'; // temporarily avoid rescheduled state
        }
        return true;
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
