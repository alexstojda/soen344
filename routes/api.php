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

Route::middleware('auth:web,nurse,doctor')->get('/user', function (Request $request) {
    return $request->user(); // wrap in resource and spit out user schedule
});

Route::apiResources([
    //users
    'nurse' => 'Users\\NurseController',
    'doctor' => 'Users\\DoctorController',
    'patient' => 'Users\\PatientController',
    //scheduling
    'availability' => 'AvailabilityController',
    'appointment' => 'AppointmentController',
    //other
    'room' => 'RoomController',
    //milestone #2
    'clinic' => 'ClinicController',
    //etc
]);

Route::get('/evan', 'AvailabilityController@possibleAppointments');

Route::apiResource('/availabilities','AvailabilityController');
Route::get('/availabilitiesByDate/{date}', 'AvailabilityController@selectDate');

Route::apiResource('/cart','CartController');
Route::post('/removeFromCart', 'CartController@destroy');


Route::apiResource('/appointments','AppointmentController');
Route::group(['prefix' => 'appointments'], function () {
    Route::get('/{from}/{to}');
    Route::get('/{scope}');
});
