<?php

namespace App\Observers;

use App\Models\Availability;
use App\Concerns\VerifiesWalkInHours;
use Carbon\CarbonPeriod;
use Log;

class AvailabilityObserver
{
    use VerifiesWalkInHours;

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

        if (Availability::where('start', '=', $availability->start)
            ->where('end', '=', $availability->end)
            ->where('is_working', '=', $availability->is_working)->exists()) {

            Log::warning('[' . __CLASS__ . '] New availability already exists in the database | '
                . $availability->start->toDateTimeString() .' | '. $availability->end->toDateTimeString());
            return false;
        }

        $minute_interval = config('bonmatin.timeslot_interval');
        if ($availability->start->diffInMinutes($availability->end) > $minute_interval) {
            Log::warning('['.__CLASS__."] New availability does not pass {$minute_interval}m interval rule | "
                . $availability->start->toDateTimeString() .' | '. $availability->end->toDateTimeString());
            foreach (CarbonPeriod::since($availability->start)->minutes($minute_interval)
                         ->end($availability->end, false) as $interval) {
                Availability::create([
                    'doctor_id' => $availability->doctor_id,
                    'start' => $interval,
                    'end' => $interval->copy()->addMinutes($minute_interval),
                    'is_working' => $availability->is_working,
                    'message' => $availability->message,
                ]);
            }
            return false;
        }

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
