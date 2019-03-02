<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        $this->mapDoctorRoutes();

        $this->mapNurseRoutes();

        //
    }

    /**
     * Define the "nurse" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapNurseRoutes()
    {
        Route::group([
            'middleware' => ['web', 'nurse', 'auth:nurse'],
            'prefix' => 'nurse',
            'as' => 'nurse.',
            'namespace' => $this->namespace,
        ], function ($router) {
            require base_path('routes/nurse.php');
        });
    }

    /**
     * Define the "doctor" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapDoctorRoutes()
    {
        Route::group([
            'middleware' => ['web', 'doctor', 'auth:doctor'],
            'prefix' => 'doctor',
            'as' => 'doctor.',
            'namespace' => $this->namespace,
        ], function ($router) {
            require base_path('routes/doctor.php');
        });
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }
}
