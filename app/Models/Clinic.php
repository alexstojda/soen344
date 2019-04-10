<?php

namespace App\Models;

use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\Clinic
 *
 * @property int $id
 * @property string $name
 * @property string $address
 * @property string $phone
 * @property string $open
 * @property string $close
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Doctor[] $doctors
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Room[] $rooms
 * @property-read array $open_time
 * @property-read array $close_time
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Clinic newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Clinic newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Clinic query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Clinic whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Clinic whereClose($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Clinic whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Clinic whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Clinic whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Clinic whereOpen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Clinic wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Clinic whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Clinic extends Model
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
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'open' => 'timestamp:H:m:s',
        'close' => 'timestamp:H:m:s',
    ];

    public function getOpenTimeAttribute()
    {
        return explode(':', $this->attributes['open']);
    }


    public function getCloseTimeAttribute()
    {
        return explode(':', $this->attributes['close']);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|Room[]|Room
     */
    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|Doctor[]|Doctor
     */
    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough|Availability[]|Availability
     */
    public function availabilities()
    {
        return $this->hasManyThrough(Availability::class, Doctor::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough|Appointment[]|Appointment
     */
    public function appointments()
    {
        return $this->hasManyThrough(Appointment::class, Doctor::class);
    }

    /**
     * @param \Illuminate\Support\Carbon|string|null $from
     * @param \Illuminate\Support\Carbon|string|null $to
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough|Room[]|Room
     */
    public function roomsBetween($from = null, $to = null)
    {
        return $this->rooms()->availableBetween($from, $to);
    }

    /**
     * @param Carbon|string $start
     * @param Carbon|string $end
     *
     * @return bool
     */
    public function isDuringWorkHours($start, $end): bool
    {
        $from = Carbon::parse($start);
        $to = Carbon::parse($end);
        $start_day = $from->copy()->startOfDay()->setTimeFrom($this->open);
        $end_day = $to->copy()->startOfDay()->setTimeFrom($this->close);
        return (
            $from->greaterThanOrEqualTo($start_day) &&
            $to->greaterThanOrEqualTo($start_day) &&
            $from->lessThanOrEqualTo($end_day) &&
            $to->lessThanOrEqualTo($end_day)
        );
    }
}
