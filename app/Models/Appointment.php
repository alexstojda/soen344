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
 * @property int|null $room_id
 * @property string $type
 * @property string $status
 * @property int $paid
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Availability[] $availabilities
 * @property-read \App\Models\Doctor $doctor
 * @property-read int $consecutive_blocks
 * @property-read int $duration
 * @property-read \Illuminate\Support\Carbon|string|null $end
 * @property-read \Illuminate\Support\Carbon|string|null $start
 * @property-read \App\Models\User $patient
 * @property-read \App\Models\Room|null $room
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Appointment between($from = null, $to = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Appointment endBefore($end = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Appointment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Appointment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Appointment ofDoctorId($doctor_id = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Appointment ofPatientId($patient_id = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Appointment ofStatus($status = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Appointment query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Appointment startAfter($start = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Appointment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Appointment whereDoctorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Appointment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Appointment wherePaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Appointment wherePatientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Appointment whereRoomId($value)
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
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'start',
        'end',
        'duration',
        'consecutive_blocks',
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
     * Scope a query to only include appointments for a given patient id
     *
     * @param  Builder $query
     * @param  int|null $patient_id
     *
     * @return Builder
     */
    public function scopeOfPatientId($query, $patient_id = null): Builder
    {
        return $patient_id === null ? $query :
            $query->where('patient_id', '=', $patient_id);
    }

    /**
     * Scope a query to only include appointments of a given status
     *
     * @param  Builder $query
     * @param  string|null $status
     *
     * @return Builder
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
     * @param  Builder $query
     * @param  Carbon|string|null $from
     * @param  Carbon|string|null $to
     *
     * @return Builder
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
     * @param  Builder $query
     * @param  Carbon|string|null $start
     *
     * @return Builder
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
     * @param  Builder $query
     * @param  Carbon|string|null $end
     *
     * @return Builder
     */
    public function scopeEndBefore($query, $end = null): Builder
    {
        return $end === null ? $query :
            $query->whereHas('availabilities', function (Builder $query) use ($end) {
                return $query->where('end', '<=', Carbon::parse($end));
            });//->with('availabilities')->orderBy('availabilities.end', 'desc');
    }
}
