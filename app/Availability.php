<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

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
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Availability availableOn(\Illuminate\Support\Carbon $at = null, $type = 'walk-in')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Availability newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Availability newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Availability query()
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
        'end'
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
     * Scope a query to only include active availabilities
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    /**
     * Scope a query to only include inactive availabilities
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnavailable($query)
    {
        return $query->where('is_available', false);
    }

    /**
     * Scope a query to only include inactive availabilities
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param Carbon|null $at
     * @param string $type
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAvailableOn($query, Carbon $at = null, $type = 'walk-in')
    {
        $start = $at ?? now();
        $end = $start->copy()->addMinutes($type === 'walk-in' ? 20 : 60);

        return $query->where('is_available','=',true)
                     ->where('start','<=', $start)
                     ->where('end','>=', $end);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id', 'id');
    }
}
