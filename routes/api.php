<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('/availabilities','AvailabilityController');
Route::get('/availabilitiesByDate/{date}', 'AvailabilityController@selectDate');

Route::apiResource('/cart','CartController');
Route::post('/createAnAppointment', 'CartController@store');
Route::post('/removeFromCart', 'CartController@destroy');

Route::apiResource('/appointments','AppointmentController');
Route::get('/doctor/{id}', 'AppointmentController@getDoctor');
Route::get('/room/{id}', 'AppointmentController@getRoom');
Route::post('/processAppointments', 'AppointmentController@store');
Route::post('/deleteAppointment', 'AppointmentController@destroy');
Route::group(['prefix' => 'appointments'], function () {
    Route::get('/{from}/{to}');
    Route::get('/{scope}');
});
