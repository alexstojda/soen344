<?php

namespace App\Observers;

use App\Availability;

class AvailabilityObserver
{
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
