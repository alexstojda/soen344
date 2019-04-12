<?php

namespace App\Models;

use App\Concerns\Model\BelongsToClinic;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use App\Concerns\FixesAvailabilityDates;
use App\Concerns\VerifiesWalkInHours;
use App\Concerns\Model\HasAppointments;

/**
 * App\Models\Room
 *
 * @property int $id
 * @property string $number
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $clinic_id
 * @property-read \App\Models\Clinic $clinic
 * @property-read \App\Models\Appointment|null $next_appointment
 * @property-read \App\Models\Appointment|null $last_appointment
 * @property-read Collection|\App\Models\Appointment[] $appointments_this_year
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Appointment[] $appointments
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Appointment[] $unscheduled_appointments
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Room availableBetween($from = null, $to = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Room newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Room newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Room query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Room whereClinicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Room whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Room whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Room whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Room whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Room whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Room extends Model
{
    use FixesAvailabilityDates;
    use VerifiesWalkInHours;
    use HasAppointments;
    use BelongsToClinic;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id',
    ];

    /**
     * Given a date checks room is available for use
     *
     * @param Carbon|string|null $from
     * @param Carbon|string|null $to
     *
     * @return bool
     */
    public function isAvailableBetween($from = null, $to = null): bool
    {
        $start = Carbon::parse($from ?? now()->startOfDay()->addHours(config('bonmatin.office_hours.open')));
        $end = ($to === null) ? $start->copy() : Carbon::parse($to);
        $this->validateDateFormat($start, $end);

        return $this->verifyWalkInHours($start, $end) && $this->appointmentsBetween($start, $end)->isEmpty();
    }

    /**
     * Given a date checks room is available for use
     *
     * @param Builder $query
     * @param Carbon|string|null $from
     * @param Carbon|string|null $to
     *
     * @return Builder
     * @throws \Exception
     */
    public function scopeAvailableBetween(Builder $query, $from = null, $to = null): Builder
    {
        $start = Carbon::parse($from ?? now()->startOfDay()->addHours(config('bonmatin.office_hours.open')));
        $end = ($to === null) ? $start->copy() : Carbon::parse($to);
        $this->validateDateFormat($start, $end);
        if ($this->verifyWalkInHours($start, $end)) {
            return $query->whereDoesntHave('appointments', function (Builder $query) use ($start, $end) {
                return $query->whereNotIn('status', ['cancelled'])
                    ->whereHas('availabilities', function (Builder $query) use ($start, $end) {
                        return $query->where('start', '>=', $start)
                            ->where('end', '<=', $end);
                    });
            });
        }
        return $query->where('id'); // WHERE id is NULL
    }
}
