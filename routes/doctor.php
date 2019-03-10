<?php

/*
|--------------------------------------------------------------------------
| Doctor Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "doctor" middleware group. Now create something great!
|
*/

Route::get('/dashboard', function () {
    $users[] = Auth::user();
    $users[] = Auth::guard()->user();
    $users[] = Auth::guard('doctor')->user();

    return view('doctor.dashboard');
})->name('dashboard');

