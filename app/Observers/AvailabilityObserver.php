<?php

namespace App\Observers;

use App\Availability;

class AvailabilityObserver
{
    /**
     * Handle the appointment "saving" event.
     *
     * @param  Availability $availability
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function saving(Availability $availability)
    {
        //Walk-in hours : 8am - 8pm
        $start_day = $availability->start->copy()->startOfDay()->addHours(8);
        $end_day = $availability->start->copy()->startOfDay()->addHours(20);
        // Check if appointment without walk-in hours
        if(!($availability->start->greaterThanOrEqualTo($start_day) &&
            $availability->end->greaterThanOrEqualTo($start_day) &&
            $availability->start->lessThanOrEqualTo($end_day) &&
            $availability->end->lessThanOrEqualTo($end_day))) {
            dump('availability rejected due to open hours rule | ' .
                $availability->start->toDateTimeString() .' | '. $availability->end->toDateTimeString() );
            return false;
        }
    }

    /**
     * Handle the availability "creating" event.
     *
     * @param  Availability  $availability
     * @return void
     */
    public function creating(Availability $availability)
    {
        //
    }

    /**
     * Handle the availability "created" event.
     *
     * @param  Availability  $availability
     * @return void
     */
    public function created(Availability $availability)
    {
        //
    }

    /**
     * Handle the availability "updated" event.
     *
     * @param  Availability  $availability
     * @return void
     */
    public function updated(Availability $availability)
    {
        //
    }

    /**
     * Handle the availability "deleted" event.
     *
     * @param  Availability  $availability
     * @return void
     */
    public function deleted(Availability $availability)
    {
        //
    }

    /**
     * Handle the availability "restored" event.
     *
     * @param  Availability  $availability
     * @return void
     */
    public function restored(Availability $availability)
    {
        //
    }

    /**
     * Handle the availability "force deleted" event.
     *
     * @param  Availability  $availability
     * @return void
     */
    public function forceDeleted(Availability $availability)
    {
        //
    }
}
