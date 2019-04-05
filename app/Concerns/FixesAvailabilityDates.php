<?php

namespace App\Concerns;

use Carbon\Carbon;

trait FixesAvailabilityDates
{

    //TODO: EVAN make it work for multiples of interval.
    // ie: DIFF % INTERVAL must have no remainder or you should add the right multiple of interval in the if
    // TLDR round up end / round down start by 20min intervals
    protected function validateDateFormat(Carbon $start, Carbon $end, $endOfDay = false): void
    {
        // if time isn't specified push end to the end of day.
        if ($end->isBefore($start) || $end->isStartOfDay()) {
            $endOfDay ? $end->endOfDay() : $end->startOfDay()->addHours(config('bonmatin.office_hours.close'));
        }
        // if difference less than one timeslot interval, reset and add one time interval.
        if ($start->diffInMinutes($end) < config('bonmatin.timeslot_interval')) {
            $end->sub($start->diff($end))->addMinutes(config('bonmatin.timeslot_interval'));
        }
    }
}
