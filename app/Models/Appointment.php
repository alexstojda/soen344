<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use App\Concerns\FixesAvailabilityDates;
use App\Http\Resources\Patient;

/**
 * App\Models\Appointment
 *
 * @property int $id
 * @property int $doctor_id
 * @property int $patient_id
 * @property int $room_id
 * @property-read int $consecutive_blocks
 * @property-read int $duration
 * @property-read \Illuminate\Support\Carbon $start
 * @property-read \Illuminate\Support\Carbon $end
 * @property string $type
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Doctor $doctor
 * @property-read \App\Models\User $patient
 * @property-read \App\Models\Room $room
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Appointment between($at = null, $to = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Appointment startAfter($start = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Appointment endBefore($end = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Appointment ofDoctorId($doctor_id = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Appointment ofPatientId($patient_id = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Appointment ofStatus($status = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Appointment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Appointment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Appointment query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Appointment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Appointment whereDoctorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Appointment whereEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Appointment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Appointment wherePatientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Appointment whereRoomId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Appointment whereStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Appointment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Appointment whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Appointment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Appointment extends Model
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
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'start',
        'end',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|Doctor
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|Patient
     */
    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|Room
     */
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany|Availability[]
     */
    public function availabilities()
    {
        return $this->belongsToMany(Availability::class);
    }

    /**
     * @return Carbon|string|null
     */
    public function getStartAttribute()
    {
        if ($this->availabilities()->exists()) {
            return $this->availabilities()->get(['start'])->first()->start;
        }
        return null;
    }

    /**
     * @return Carbon|string|null
     */
    public function getEndAttribute()
    {
        if ($this->availabilities()->exists()) {
            return $this->availabilities()->get(['end'])->last()->end;
        }
        return null;
    }

    /**
     * @return int
     */
    public function getConsecutiveBlocksAttribute(): int
    {
        switch ($this->type) {
            case 'checkup':
                return 3;
            case 'walk-in': default:
                return 1;
        }
    }

    /**
     * @return int
     */
    public function getDurationAttribute(): int
    {
        return $this->consecutive_blocks * config('bonmatin.timeslot_interval');
    }

    /**
     * Scope a query to only include appointments for a given doctor id
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  int|null $doctor_id
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfDoctorId($query, $doctor_id = null): Builder
    {
        return $doctor_id === null ? $query :
            $query->where('doctor_id', '=', $doctor_id);
    }

    /**
     * Scope a query to only include appointments for a given patient id
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  int|null $patient_id
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfPatientId($query, $patient_id = null): Builder
    {
        return $patient_id === null ? $query :
            $query->where('patient_id', '=', $patient_id);
    }

    /**
     * Scope a query to only include appointments of a given status
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string|null $status
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfStatus($query, $status = null): Builder
    {
        return $status === null ? $query->whereHas('availabilities', function () {
        })->whereNotIn('status', ['unscheduled', 'cancelled']) :
            $query->where('status', '=', $status);
    }

    /**
     * Scope a query to only include appointments between 2 dates.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  Carbon|string|null $from
     * @param  Carbon|string|null $to
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBetween($query, $from = null, $to = null): Builder
    {
        $start = Carbon::parse($from);
        $end = ($to === null) ? $start->copy() : Carbon::parse($to);
        $this->validateDateFormat($start, $end);

        return $query->whereHas('availabilities', function (Builder $query) use ($start, $end) {
            return $query->where('start', '>=', $start)
                ->where('end', '<=', $end)
                ->whereNotIn('status', ['unscheduled', 'cancelled']);
        });
    }

    /**
     * Scope a query to only include records that start after given value
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  Carbon|string|null $start
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStartAfter($query, $start = null): Builder
    {
        return $start === null ? $query :
            $query->whereHas('availabilities', function (Builder $query) use ($start) {
                return $query->where('start', '>=', Carbon::parse($start));
            });//->with('availabilities')->orderBy('availabilities.start');
    }

    /**
     * Scope a query to only include records that end before given value
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  Carbon|string|null $end
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEndBefore($query, $end = null): Builder
    {
        return $end === null ? $query :
            $query->whereHas('availabilities', function (Builder $query) use ($end) {
                return $query->where('end', '<=', Carbon::parse($end));
            });//->with('availabilities')->orderBy('availabilities.end', 'desc');
    }
}