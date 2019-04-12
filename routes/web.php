<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->middleware('auth:web')->name('home');
Route::get('/checkout', 'CartController@showCheckoutPage')->middleware('auth:web');

Route::get('/createAppointment', 'AppointmentController@showCreateAppointmentPage');
Route::put('/appointmentUpdate/{appointment}', 'AppointmentController@update');
Route::get('/viewAppointments', 'AppointmentController@showViewAppointmentsPage');

Route::group(['prefix' => 'doctor'], function () {
    Route::get('/availabilities', 'AvailabilityController@showAvailabilitiesPage')->middleware('auth:doctor');
    Route::get('/addAvailability', 'AvailabilityController@showAddAvailabilityPage')->middleware('auth:doctor');
    Route::get('/viewAppointments', 'AppointmentController@showViewAppointmentsPage')->middleware('auth:doctor');
});

Route::group(['prefix' => 'nurse'], function () {
    Route::get('/book', 'AppointmentController@showNurseCreateAppointmentPage')->middleware('auth:nurse');
});

Route::group(['prefix' => 'patient'], function () {
    Route::get('/book', 'AppointmentController@showPatientCreateAppointmentPage')->middleware('auth:web');
});
