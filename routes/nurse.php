<?php

Route::get('/home', function () {
    $users[] = Auth::user();
    $users[] = Auth::guard()->user();
    $users[] = Auth::guard('nurse')->user();

    //dd($users);

    return view('nurse.home');
})->name('home');

