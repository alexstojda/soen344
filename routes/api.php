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

// Fancy route that builds all possible appointment start times based on given filter
Route::get('/evan', 'AvailabilityController@possibleAppointments');
Route::get('/appointment/available', 'AvailabilityController@possibleAppointments');
