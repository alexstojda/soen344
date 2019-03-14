<?php

namespace App\Observers;

use App\Availability;

class AvailabilityObserver
{
    use Concerns\VerifiesWalkInHours;

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
        //Reset time to start of minute for consistency...
        $availability->start = $availability->start->startOfMinute();
        $availability->end = $availability->end->startOfMinute();

        // Check if appointment without walk-in hours
        if (!$this->verifyWalkInHours($availability->start, $availability->end)) {
            dump('availability rejected due to open hours rule | ' .
                $availability->start->toDateTimeString() .' | '. $availability->end->toDateTimeString());
            return false;
        }

        return true;
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
