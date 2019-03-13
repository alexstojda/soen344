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
Route::post('/processAppointments/{id}', 'AppointmentController@finalize');
Route::delete('/deleteAppointment/{appointment}', 'AppointmentController@destroy');
Route::get('/cart/{id}', 'CartController@getById');

Route::get('/createAppointment', 'AppointmentController@showCreateAppointmentPage');
Route::get('/viewAppointments', 'AppointmentController@showViewAppointmentsPage');

Route::group(['prefix' => 'doctor'], function () {
    Route::get('/addAvailability', 'AvailabilityController@showAddAvailabilityPage')->middleware('auth:doctor');
    Route::post('/api/addAvailability', 'AvailabilityController@store');
    Route::get('/viewAppointments', 'AppointmentController@showViewAppointmentsPage')->middleware('auth:doctor');
});
