<?php

namespace App\Observers;

use App\Models\Availability;
use Carbon\CarbonPeriod;
use Log;

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
        //Reset time to start of minute for consistency...
        $availability->start = $availability->start->startOfMinute();
        $availability->end = $availability->end->startOfMinute();

        $minute_interval = config('bonmatin.timeslot_interval');
        if ($availability->start->diffInMinutes($availability->end) > $minute_interval) {
            $msg = '['.__CLASS__."] New availability does not pass {$minute_interval}m interval rule | "
                . $availability->start->toDateTimeString() .' | '. $availability->end->toDateTimeString();
            dump($msg);
            Log::warning($msg);
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
        if (!$availability->clinic->isDuringWorkHours($availability->start, $availability->end)) {
            abort(412, 'Availability rejected due to open hours rule | ' .
                    $availability->start->toDateTimeString() .' | '. $availability->end->toDateTimeString());
            return false;
        }

        return true;
    }

    /**
     * Handle the availability "creating" event.
     *
     * @param  Availability  $availability
     * @return bool
     */
    public function creating(Availability $availability)
    {
        dump("creating: $availability->doctor_id, $availability->start, $availability->end");

        $col = Availability::whereDoctorId($availability->doctor_id)
            ->whereStart($availability->start)
            ->whereEnd($availability->end);

        if ($col->count() > 0) {
            $col->each(function (Availability $old) use ($availability) {
                $msg = '['.__CLASS__.'] availability already exists... overwrite | ID:' . $old->id . ' | ' .
                        $availability->start->toDateTimeString() .' | '. $availability->end->toDateTimeString();
                dump($msg);
                Log::notice($msg);
                $old->is_working = $availability->is_working;
                $old->message = $availability->message;
                $old->save();
            });
            return false;
        }
        return true;
    }

    /**
     * Handle the availability "created" event.
     *
     * @param  Availability  $availability
     * @return void
     */
    public function created(Availability $availability)
    {
        dump("created:  $availability->doctor_id, $availability->start, $availability->end, #$availability->id");
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
