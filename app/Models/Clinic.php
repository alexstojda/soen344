<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Clinic
 *
 * @property int $id
 * @property string $name
 * @property string $address
 * @property string $phone
 * @property mixed $open
 * @property mixed $close
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Doctor[] $doctors
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Room[] $rooms
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
}
