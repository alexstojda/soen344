<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use \Illuminate\Database\Eloquent\Collection;

/**
 * App\Room
 *
 * @property int $id
 * @property string $number
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Appointment[] $appointments
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Room availableBetween($from = null, $to = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Room newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Room newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Room query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Room whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Room whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Room whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Room whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Room whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Room extends Model
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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|Collection|Appointment[]|Appointment
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'room_id', 'id');
    }

    /**
     * Given a date grab all appointments in this room
     *
     * @param Carbon|string|null $from
     * @param Carbon|string|null $to
     *
     * @return Collection|Availability[]
     */
    public function appointmentsBetween($from = null, $to = null)
    {
        return $this->appointments()->between($from, $to)->get();
    }

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
        $end = ($to === null) ? $start->copy()->startOfDay()->addHours(config('bonmatin.office_hours.close')) : Carbon::parse($to);

        $open = $start->copy()->startOfDay()->addHours(config('bonmatin.office_hours.open'))->subSecond();
        $close = $end->copy()->startOfDay()->addHours(config('bonmatin.office_hours.close'))->addSecond();

        return $start->isAfter($open) && $end->isBefore($close) &&
               $this->appointmentsBetween($start, $end)->isEmpty();
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
        $end = ($to === null) ? $start->copy()->startOfDay()->addHours(config('bonmatin.office_hours.close')) : Carbon::parse($to);

        $open = $start->copy()->startOfDay()->addHours(config('bonmatin.office_hours.open'))->subSecond();
        $close = $end->copy()->startOfDay()->addHours(config('bonmatin.office_hours.close'))->addSecond();

        if ($start->isAfter($open) && $end->isBefore($close)) {
            return $query->whereDoesntHave('appointments', function (Builder $query) use ($start, $end) {
                return $query->whereNotIn('status', ['cancelled'])
                    ->where('start', '>=', $start)
                    ->where('end', '<=', $end);
            });
        }
        return $query->where('id'); // WHERE id is NULL
    }
}
