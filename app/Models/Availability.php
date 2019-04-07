<?php

namespace App\Models;

use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use App\Concerns\FixesAvailabilityDates;

/**
 * App\Models\Availability
 *
 * @property int $id
 * @property int $doctor_id
 * @property \Illuminate\Support\Carbon $start
 * @property \Illuminate\Support\Carbon $end
 * @property bool $is_working
 * @property string|null $message
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Appointment[] $appointments
 * @property-read \App\Models\Doctor $doctor
 * @property-read \App\Models\Appointment|null $appointment
 * @property-read bool $is_available
 * @property-read bool $is_booked
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Availability available()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Availability between($from = null, $to = null, $available = true)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Availability consecutive($count = 3, $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Availability endBefore($end = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Availability length($length = '60min', $operator = '>=')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Availability newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Availability newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Availability ofDoctorId($doctor_id = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Availability ofClinicId($clinic_id = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Availability query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Availability startAfter($start = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Availability startBefore($start = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Availability unavailable()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Availability whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Availability whereDoctorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Availability whereEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Availability whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Availability whereIsWorking($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Availability whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Availability whereStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Availability whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Availability extends Model
{
    use FixesAvailabilityDates;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_working'   => 'boolean',
        'is_booked'    => 'boolean',
        'is_available' => 'boolean',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'start',
        'end',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'is_booked',
        'is_available'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|Doctor
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany|Appointment[]|Appointment
     */
    public function appointments()
    {
        return $this->belongsToMany(Appointment::class);
    }

    /**
     * An availability should only ever be linked to a single appointment despite the many-to-many
     * relationship setup so we use this on top of the belongsToMany for convenience
     *
     * @return Appointment|null
     */
    public function appointment()
    {
        return $this->appointments()->first();
    }

    /**
     * Double the convenience by providing access to the method as an attribute
     * essentially mimicking how a one-to-one relationship is used on a model.
     *
     * @return Appointment|null
     */
    public function getAppointmentAttribute()
    {
        return $this->appointment();
    }

    /**
     * @return bool
     */
    public function getIsBookedAttribute(): bool
    {
        return $this->appointment() instanceof Appointment;
    }

    /**
     * @return bool
     */
    public function getIsAvailableAttribute(): bool
    {
        return $this->is_working && !$this->is_booked &&
            $this->doctor->clinic->roomsBetween($this->start, $this->end)->count() > 0;
    }

    /**
     * Scope a query to only include records that start after given value
     *
     * @param  Builder $query
     * @param  Carbon|string|null $start
     *
     * @return Builder
     */
    public function scopeStartAfter($query, $start = null): Builder
    {
        return $start === null ? $query :
            $query->where('start', '>=', Carbon::parse($start));
    }

    /**
     * Scope a query to only include records that start before given value
     *
     * @param  Builder $query
     * @param  Carbon|string|null $start
     *
     * @return Builder
     */
    public function scopeStartBefore($query, $start = null): Builder
    {
        return $start === null ? $query :
            $query->where('start', '<=', Carbon::parse($start));
    }

    /**
     * Scope a query to only include records that start after given value
     *
     * @param  Builder $query
     * @param  Carbon|string|null $end
     *
     * @return Builder
     */
    public function scopeEndBefore($query, $end = null): Builder
    {
        return $end === null ? $query :
            $query->where('end', '<=', Carbon::parse($end));
    }

    /**
     * Scope a query to narrow availability dates
     *
     * @param  Builder $query
     * @param  Carbon|string|null $from
     * @param  Carbon|string|null $to
     * @param  boolean $available
     *
     * @return Builder
     */
    public function scopeBetween($query, $from = null, $to = null, $available = true): Builder
    {
        $start = Carbon::parse($from);
        $end = ($to === null) ? $start->copy() : Carbon::parse($to);
        $this->validateDateFormat($start, $end, true);

        return $query->where('is_working', $available)
            ->where('start', '>=', $start)
            ->where('end', '<=', $end);
    }

    /**
     * Scope a query to only include availabilities for a given doctor id
     *
     * @param  Builder $query
     * @param  int|null $doctor_id
     *
     * @return Builder
     */
    public function scopeOfDoctorId($query, $doctor_id = null): Builder
    {
        return $doctor_id === null ? $query :
            $query->where('doctor_id', '=', $doctor_id);
    }

    /**
     * Scope a query to only include availabilities for a given clinic id
     *
     * @param  Builder $query
     * @param  int|null $clinic_id
     *
     * @return Builder
     */
    public function scopeOfClinicId($query, $clinic_id = null): Builder
    {
        return $clinic_id === null ? $query :
            $query->whereHas('doctor', function (Builder $query) use ($clinic_id) {
                return $query->where('clinic_id', '=', $clinic_id);
            });
    }

    /**
     * Scope a query to only include unavailable records
     *
     * @param  Builder $query
     * @return Builder
     */
    public function scopeAvailable($query): Builder
    {
        return $query->where('is_working', true)->whereDoesntHave('appointments');
    }

    /**
     * Scope a query to only include available records
     *
     * @param  Builder $query
     * @return Builder
     */
    public function scopeUnavailable($query): Builder
    {
        return $query->orWhere('is_working', false)->orWhereHas('appointments');
    }

    /**
     *
     * @param Builder $query
     * @param string|int $count
     * @param string $operator
     *
     * @return \Illuminate\Support\Collection
     */
    public function scopeConsecutive($query, $count = 3, $operator = '=')
    {
        return $this->scopeLength($query, ((int) $count * config('bonmatin.timeslot_interval')) . 'min', $operator);
    }

    /**
     *
     * @param Builder $query
     * @param string $length
     * @param string $operator
     *
     * @return \Illuminate\Support\Collection
     */
    public function scopeLength($query, $length = '60min', $operator = '>=')
    {
        $interval = CarbonInterval::createFromDateString($length);

        return self::mergeTimeslots($query->get())->filter(function ($item) use ($interval, $operator) {
            $diff = $interval->compare((new Carbon($item['start']))->diffAsCarbonInterval(new Carbon($item['end'])));
            switch ($operator) {
                case '<':
                    return 0 < $diff;
                case '>':
                    return 0 > $diff;
                case '<=':
                    return 0 <= $diff;
                case '>=':
                    return 0 >= $diff;
                case '<=>':
                    return 0 <=> $diff;
                case '=': default:
                    return 0 === $diff;
            }
        })->values();
    }


    /**
     * @param null|Collection|static[] $availabilities
     *
     * @return \Illuminate\Support\Collection
     */
    public static function mergeTimeslots($availabilities = null)
    {
        $availabilities = $availabilities ?? self::get();
        $sorted = $availabilities->sortBy('doctor_id')->sortBy('start')->values();

        $merged = collect();
        $current = collect();

        $sorted->each(function (Availability $item, $index) use ($sorted, $merged, &$current) {
            $current->add($item);
            $next = $sorted->get($index + 1);
            if (!($next !== null &&
                $item->doctor_id === $next->doctor_id &&
                $item->is_working === $next->is_working &&
                $item->end->equalTo($next->start))) {
                $merged->add(new Availability([
                    'ids'           => $current->pluck('id')->toArray(),
                    'doctor_id'     => $current->first()->doctor_id,
                    'is_working'    => $current->first()->is_working,
                    'start'         => $current->first()->start,
                    'end'           => $current->last()->end,
                    'created_at'    => $current->first()->created_at,
                    'updated_at'    => $current->first()->updated_at,
                ]));
                $current = collect();
            }
        });

        return $merged;
    }
}
