<?php

namespace App\Concerns\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Clinic;

/**
 * Trait HasAppointments
 * @package App\Concerns
 *
 * @mixin Model
 */
trait BelongsToClinic
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|Clinic
     */
    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    /**
     * Scope a query to only include availabilities for a given doctor id
     *
     * @param  Builder $query
     * @param  int|null $doctor_id
     *
     * @return Builder
     */
    public function scopeOfClinicId($query, $doctor_id = null): Builder
    {
        return $doctor_id === null ? $query :
            $query->where('clinic_id', '=', $doctor_id);
    }

}
