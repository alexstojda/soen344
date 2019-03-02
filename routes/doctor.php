<?php

Route::get('/home', function () {
    $users[] = Auth::user();
    $users[] = Auth::guard()->user();
    $users[] = Auth::guard('doctor')->user();

    //dd($users);

    return view('doctor.home');
})->name('home');

