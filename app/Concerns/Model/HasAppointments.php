<?php

namespace App\Concerns\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use App\Models\Appointment;

/**
 * Trait HasAppointments
 * @package App\Concerns
 *
 * @mixin Model
 */
trait HasAppointments
{

    /**
     * @return Collection|Appointment[]
     */
    public function getAppointmentsThisYearAttribute()
    {
        return $this->appointmentsBetween(Carbon::now()->startOfYear(), Carbon::now()->endOfYear());
    }

    /**
     * @return Appointment|null
     */
    public function getNextAppointmentAttribute(): ?Appointment
    {
        return $this->appointments()->ofStatus('active')->startAfter(Carbon::now())->get()
            ->sortBy('availabilities.start')->first();
    }

    /**
     * @return Appointment|null
     */
    public function getLastAppointmentAttribute(): ?Appointment
    {
        return $this->appointments()->ofStatus('complete')->endBefore(Carbon::now())->get()
            ->sortByDesc('availabilities.end')->first();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|Collection|Appointment[]|Appointment
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class)->whereHas('availabilities');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|Collection|Appointment[]|Appointment
     */
    public function unscheduled_appointments()
    {
        return $this->hasMany(Appointment::class)->whereDoesntHave('availabilities');
    }

    /**
     * Given a date grab all appointments in this room
     *
     * @param Carbon|string|null $from
     * @param Carbon|string|null $to
     *
     * @return Collection|Appointment[]
     */
    public function appointmentsBetween($from = null, $to = null)
    {
        return $this->appointments()->between($from, $to)->get();
    }
}
