<?php

namespace App\Models;

use App\Concerns\Model\BelongsToClinic;
use App\Notifications\NurseResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * App\Models\Nurse
 *
 * @property int $id
 * @property string $access_id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property int $clinic_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Clinic $clinic
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nurse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nurse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nurse query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nurse whereAccessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nurse whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nurse whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nurse whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nurse whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nurse whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nurse wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nurse whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nurse whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Nurse extends Authenticatable
{
    use Notifiable;
    use BelongsToClinic;

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
     * Send the password reset notification.
     *
     * @param  string $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new NurseResetPassword($token));
    }
}
