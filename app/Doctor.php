<?php

namespace App;

use App\Notifications\DoctorResetPassword;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * App\Doctor
 *
 * @property int $id
 * @property int $permit_id
 * @property string $first_name
 * @property string $last_name
 * @property string $speciality
 * @property string $city
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Appointment[] $appointments
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Availability[] $availabilities
 * @property-read string $name
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Doctor availableBetween($from = null, $to = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Doctor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Doctor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Doctor query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Doctor whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Doctor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Doctor whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Doctor whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Doctor whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Doctor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Doctor whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Doctor wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Doctor wherePermitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Doctor whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Doctor whereSpeciality($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Doctor whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Doctor extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|Collection|Availability[]|Availability
     */
    public function availabilities()
    {
        return $this->hasMany(Availability::class, 'doctor_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|Collection|Appointment[]|Appointment
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'doctor_id', 'id');
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
            })->whereHas('availabilities', function (Builder $query) use ($start, $end) {
                $query->where('is_available', '=', true)
                    ->where('start', '>=', $start)
                    ->where('end', '<=', $end);
            });
        }
        return $query->where('id'); // WHERE id is NULL
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
     * Given a date grab all doctors availabilities
     *
     * @param Carbon|string|null $from
     * @param Carbon|string|null $to
     *
     * @return Collection|Availability[]
     */
    public function availabilitiesBetween($from = null, $to = null)
    {
        return $this->availabilities()->between($from, $to)->get();
    }

    /**
     * Given a date checks if the doctor is available or booked
     *
     * @param Carbon|string|null $from
     * @param Carbon|string|null $to
     *
     * @return bool
     */
    public function isAvailableBetween($from = null, $to = null): bool
    {
        $start = Carbon::parse($from ?? now()->startOfDay()->addHours(config('bonmatin.office_hours.open')));
        $end = Carbon::parse($to ?? $start->copy()->startOfDay()->addHours(config('bonmatin.office_hours.close')));

        $open = $start->copy()->startOfDay()->addHours(config('bonmatin.office_hours.open'))->subSecond();
        $close = $end->copy()->startOfDay()->addHours(config('bonmatin.office_hours.close'))->addSecond();

        return $start->isAfter($open) && $end->isBefore($close) &&
            $this->appointmentsBetween($start, $end)->isEmpty() &&
            $this->availabilitiesBetween($start, $end)->isNotEmpty();
    }


    /**
     * @param Carbon|string|null $date
     * @return mixed
     */
    public function schedule($date = null)
    {
        //Grab Availabilities (available) sort & group into days

        //Grab Appointments (active or in cart) sort & group into days

        //Sum availabilities that overlap into a single availability object


        $daily_schedule = $this->availabilities()->available()->get()
            ->sortBy('start')->groupBy(function (Availability $item) {
                return $item->start->toDateString();
            });

        if ($date !== null) {
            return $daily_schedule->get(Carbon::parse($date)->toDateString());
        }

        return $daily_schedule;
    }

    /**
     * Send the password reset notification.
     *
     * @param  string $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new DoctorResetPassword($token));
    }
}
