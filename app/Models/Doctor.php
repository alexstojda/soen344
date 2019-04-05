<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Concerns\Model\HasAppointments;
use App\Concerns\Model\HasAvailabilities;
use App\Concerns\FixesAvailabilityDates;
use App\Concerns\VerifiesWalkInHours;
use App\Notifications\DoctorResetPassword;

/**
 * App\Models\Doctor
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
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Appointment[] $appointments
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Availability[] $availabilities
 * @property-read string $name
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Doctor availableBetween($from = null, $to = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Doctor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Doctor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Doctor query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Doctor whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Doctor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Doctor whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Doctor whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Doctor whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Doctor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Doctor whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Doctor wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Doctor wherePermitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Doctor whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Doctor whereSpeciality($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Doctor whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Doctor extends Authenticatable
{
    use Notifiable;
    use FixesAvailabilityDates;
    use VerifiesWalkInHours;
    use HasAvailabilities;
    use HasAppointments;

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
        $start = Carbon::parse($from ?? Carbon::now()->startOfDay()->addHours(config('bonmatin.office_hours.open')));
        $end = ($to === null) ? $start->copy() : Carbon::parse($to);
        if ($this->verifyWalkInHours($start, $end)) {
            return $query->whereDoesntHave('appointments', function (Builder $query) use ($start, $end) {
                return $query->whereNotIn('status', ['cancelled', 'cart'])
                    ->where('start', '>=', $start)
                    ->where('end', '<=', $end);
            })->whereHas('availabilities', function (Builder $query) use ($start, $end) {
                $query->where('is_working', '=', true)
                    ->where('start', '>=', $start)
                    ->where('end', '<=', $end);
            });
        }
        return $query->where('id'); // WHERE id is NULL
    }

    /**
     * Given a date grab all doctors availabilities
     *
     * @param Carbon|string|null $from
     * @param Carbon|string|null $to
     *
     * @return Collection|Availability[]|Availability
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
        $end = Carbon::parse($to ?? $start->copy());

        return $this->verifyWalkInHours($start, $end) &&
            $this->appointmentsBetween($start, $end)->isEmpty() &&
            $this->availabilitiesBetween($start, $end)->isNotEmpty();
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
