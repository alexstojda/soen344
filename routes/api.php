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
    //office
    //etc
]);

Route::apiResource('/availabilities','AvailabilityController');
Route::get('/availabilitiesByDate/{date}', 'AvailabilityController@selectDate');

Route::apiResource('/cart','CartController');
Route::post('/createAnAppointment', 'AppointmentController@store');
Route::post('/removeFromCart', 'CartController@destroy');


Route::apiResource('/appointments','AppointmentController');
Route::group(['prefix' => 'appointments'], function () {
    Route::get('/{from}/{to}');
    Route::get('/{scope}');
});
