<?php

namespace App\Concerns\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Carbon;
use App\Models\Availability;

/**
 * Trait HasAvailabilities
 * @package App\Concerns
 *
 * @mixin Model
 */
trait HasAvailabilities
{


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|Collection|Availability[]|Availability
     */
    public function availabilities()
    {
        return $this->hasMany(Availability::class)->available();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|Collection|Availability[]|Availability
     */
    public function unavailabilities()
    {
        return $this->hasMany(Availability::class)->unavailable();
    }


}
