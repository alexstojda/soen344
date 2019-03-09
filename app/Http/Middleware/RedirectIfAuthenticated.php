<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            switch ($guard) {
                case 'doctor':
                    return redirect()->route('doctor.dashboard');
                case 'nurse':
                    return redirect()->route('nurse.dashboard');
                case 'patient': default:
                    return redirect()->route('home');
            }
        }

        return $next($request);
    }
}
