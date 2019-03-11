<?php

namespace App\Observers\Concerns;


use Illuminate\Support\Carbon;

trait VerifiesWalkInHours
{
    /**
     * @param Carbon $start
     * @param Carbon $end
     * @param int $start_hour defaults to 8am
     * @param int $end_hour   defaults to 8pm
     *
     * @return bool
     */
    protected function verifyWalkInHours(Carbon $start, Carbon $end, $start_hour = 8, $end_hour = 20)
    {
        //Walk-in hours : 8am - 8pm
        $start_day = $start->copy()->startOfDay()->addHours($start_hour);
        $end_day = $start->copy()->startOfDay()->addHours($end_hour);
        // Check if appointment without walk-in hours
        return (
            $start->greaterThanOrEqualTo($start_day) &&
            $end->greaterThanOrEqualTo($start_day) &&
            $start->lessThanOrEqualTo($end_day) &&
            $end->lessThanOrEqualTo($end_day)
        );
    }
}