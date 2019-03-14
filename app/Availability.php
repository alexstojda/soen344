<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Availability
 *
 * @property int $id
 * @property int $doctor_id
 * @property \Illuminate\Support\Carbon $start
 * @property \Illuminate\Support\Carbon $end
 * @property bool $is_available
 * @property string|null $reason_of_unavailability
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Doctor $doctor
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Availability available()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Availability between($at = null, $to = null, $available = true)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Availability endBefore($end = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Availability newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Availability newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Availability ofDoctorId($doctor_id = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Availability query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Availability startAfter($start = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Availability startBefore($start = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Availability unavailable()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Availability whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Availability whereDoctorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Availability whereEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Availability whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Availability whereIsAvailable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Availability whereReasonOfUnavailability($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Availability whereStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Availability whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Availability extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id',
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
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_available' => 'boolean',
    ];

    /**
     * Scope a query to only include records that start after given value
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  Carbon|string|null $start
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStartAfter($query, $start = null)
    {
        return $start === null ? $query :
            $query->where('start', '>=', Carbon::parse($start));
    }

    /**
     * Scope a query to only include records that start before given value
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  Carbon|string|null $start
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStartBefore($query, $start = null)
    {
        return $start === null ? $query :
            $query->where('start', '<=', Carbon::parse($start));
    }

    /**
     * Scope a query to only include records that start after given value
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  Carbon|string|null $end
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEndBefore($query, $end = null)
    {
        return $end === null ? $query :
            $query->where('end', '<=', Carbon::parse($end));
    }

    /**
     * Scope a query to narrow availability dates
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  Carbon|string|null $at
     * @param  Carbon|string|null $to
     * @param  boolean $available
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBetween($query, $at = null, $to = null, $available = true)
    {
        $start = Carbon::parse($at);
        $end = ($to === null) ? $start->copy()->endOfDay() : Carbon::parse($to);

        return $query->where('is_available', $available)
            ->where('start', '>=', $start)
            ->where('end', '<=', $end);
    }

    /**
     * Scope a query to only include availabilities for a given doctor id
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  int|null $doctor_id
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfDoctorId($query, $doctor_id = null)
    {
        return $doctor_id === null ? $query :
            $query->where('doctor_id', '=', $doctor_id);
    }

    /**
     * Scope a query to only include unavailable records
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    /**
     * Scope a query to only include available records
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnavailable($query)
    {
        return $query->where('is_available', false);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id', 'id');
    }
}
