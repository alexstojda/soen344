<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Appointment
 *
 * @property int $id
 * @property int $doctor_id
 * @property int $patient_id
 * @property int $room_id
 * @property \Illuminate\Support\Carbon $start
 * @property \Illuminate\Support\Carbon $end
 * @property string $type
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Doctor $doctor
 * @property-read \App\User $patient
 * @property-read \App\Room $room
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Appointment between(\Illuminate\Support\Carbon $at = null, $type = 'walk-in')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Appointment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Appointment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Appointment ofStatus($status)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Appointment ofType($type)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Appointment query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Appointment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Appointment whereDoctorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Appointment whereEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Appointment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Appointment wherePatientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Appointment whereRoomId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Appointment whereStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Appointment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Appointment whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Appointment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Appointment extends Model
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
     * Scope a query to only include appointments of a given type.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  mixed $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope a query to only include appointments of a given status.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  mixed $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfStatus($query, $status)
    {
        return $query->where('type', $status);
    }


    /**
     * Scope a query to narrow appointment dates
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param Carbon|null $at
     * @param string $type
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBetween($query, Carbon $at = null, $type = 'walk-in')
    {
        $start = $at ?? now();
        $end = $start->copy()->addMinutes($type === 'walk-in' ? 20 : 60);

        return $query->whereIn('status', ['confirmed', 'complete'])
            ->where('start','<=', $start)
            ->where('end','>=', $end);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id', 'id');
    }

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id', 'id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id', 'id');
    }
}
