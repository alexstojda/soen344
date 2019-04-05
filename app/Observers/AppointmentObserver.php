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
        if ($appointment->availabilities()->exists()) {
            // Check if appointment without walk-in hours
            /*        if (!$this->verifyWalkInHours($appointment->start, $appointment->end)) {
                        dump('rejected due to open hours rule | ' .
                            $appointment->start->toDateTimeString() .' | '. $appointment->end->toDateTimeString());
                        return false;
                    }*/

            /*        //Check if doctor is available
                    if (!$appointment->doctor->isAvailableBetween($appointment->start, $appointment->end)) {
                        dump('rejected due to doctor availability rule | ' . "Doc ID: {$appointment->doctor_id} | " .
                            $appointment->start->toDateTimeString() .' | '. $appointment->end->toDateTimeString());
                        return false;
                    }*/

            //Check if room is available
            /*        if (!$appointment->room->isAvailableBetween($appointment->start, $appointment->end)) {
                        dump('rejected due to  room availability rule | ' . "Room ID: {$appointment->room_id} | " .
                            $appointment->start->toDateTimeString() .' | '. $appointment->end->toDateTimeString());
                        return false;
                    }*/

            switch ($appointment->status) {
                case 'cancelled':
                    //$appointment->delete();
                    break;
                case 'rescheduled':
                    //TODO: use rescheduled state to notify patient.
                    //      set appointment to rescheduled if room, doctor or time changes
                    $appointment->status = 'active'; // temporarily avoid rescheduled state
                    break;
                case 'active':
                    if ($appointment->start->isPast() && $appointment->end->isPast()) {
                        $appointment->status = 'complete';
                    }
                    break;
                case 'complete':
                    if ($appointment->start->isFuture() && $appointment->end->isFuture()) {
                        $appointment->status = 'active';
                    }
                    break;

            }
        } else {
            $appointment->status = 'unscheduled';
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
