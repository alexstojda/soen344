<?php

namespace App;

use App\Notifications\NurseResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * App\Nurse
 *
 * @property int $id
 * @property string $access_id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Nurse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Nurse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Nurse query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Nurse whereAccessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Nurse whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Nurse whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Nurse whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Nurse whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Nurse whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Nurse wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Nurse whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Nurse whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Nurse extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
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
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new NurseResetPassword($token));
    }
}
