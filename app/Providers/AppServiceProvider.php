<?php

namespace App\Providers;

use App\User;
use App\Observers\UserObserver;
use App\Appointment;
use App\Observers\AppointmentObserver;
use App\Availability;
use App\Observers\AvailabilityObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //Load Model Observers
        User::observe(UserObserver::class);
        Appointment::observe(AppointmentObserver::class);
        Availability::observe(AvailabilityObserver::class);
    }
}
