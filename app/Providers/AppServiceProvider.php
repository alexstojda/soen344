<?php

namespace App\Providers;

use App\Models\User;
use App\Observers\UserObserver;
use App\Models\Appointment;
use App\Observers\AppointmentObserver;
use App\Models\Availability;
use App\Observers\AvailabilityObserver;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
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
        // Enable pagination
        if (!Collection::hasMacro('paginate')) {
            Collection::macro(
                'paginate',
                function ($perPage = 15, $page = null, $options = []) {
                    if (Paginator::resolveCurrentPage()) {
                        $page = $page ?: Paginator::resolveCurrentPage();
                    } else {
                        $page = $page ?: 1;
                    }
                    return (new LengthAwarePaginator(
                        $this->forPage($page, $perPage)->values()->all(),
                        $this->count(),
                        $perPage,
                        $page,
                        $options
                    ))->withPath(Paginator::resolveCurrentPath());
                }
            );
        }
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
