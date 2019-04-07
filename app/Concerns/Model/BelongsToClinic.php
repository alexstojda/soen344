<?php

namespace App\Concerns\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
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
}
