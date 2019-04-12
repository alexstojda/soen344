<?php

namespace App\Concerns;

use Carbon\Carbon;

trait VerifiesWalkInHours
{
    /**
     * @param Carbon $start
     * @param Carbon $end
     * @param int|null $start_hour defaults to 8am
     * @param int|null $end_hour   defaults to 8pm
     *
     * @return bool
     */
    protected function verifyWalkInHours(Carbon $start, Carbon $end, $start_hour = null, $end_hour = null): bool
    {
        //Walk-in hours : 8am - 8pm
        $start_day = $start->copy()->startOfDay()->addHours($start_hour ?? config('bonmatin.office_hours.open'));
        $end_day = $start->copy()->startOfDay()->addHours($end_hour ?? config('bonmatin.office_hours.close'));
        return (
            $start->greaterThanOrEqualTo($start_day) &&
            $end->greaterThanOrEqualTo($start_day) &&
            $start->lessThanOrEqualTo($end_day) &&
            $end->lessThanOrEqualTo($end_day)
        );
    }
}
