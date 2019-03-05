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

//Route::get('/home', 'HomeController@index')->name('home');

Route::get('/createAppointment', 'AppointmentController@showCreateAppointmentPage');
Route::get('/viewAppointments', 'AppointmentController@showViewAppointmentsPage');

Route::group(['prefix' => 'api'], function () {
    Route::apiResource('/availabilities','AvailabilityController');
    Route::get('/availabilitiesByDate', 'AvailabilityController@selectDate');

    Route::apiResource('/cart','CartController@index');
    Route::post('/createAnAppointment', 'CartController@store');
    Route::get('/checkout', 'CartController@showCheckoutPage');

    Route::apiResource('/appointments','AppointmentController');
    Route::get('/doctor/{id}', 'AppointmentController@getDoctor');
    Route::get('/room/{id}', 'AppointmentController@getRoom');
    Route::post('/processAppointments', 'AppointmentController@store');
    Route::group(['prefix' => 'appointments'], function () {
        Route::get('/{from}/{to}');
        Route::get('/{scope}');
    });
});

Route::group(['prefix' => 'nurse'], function () {
  Route::get('/login', 'NurseAuth\LoginController@showLoginForm')->name('login');
  Route::post('/login', 'NurseAuth\LoginController@login');
  Route::post('/logout', 'NurseAuth\LoginController@logout')->name('logout');

  Route::get('/register', 'NurseAuth\RegisterController@showRegistrationForm')->name('register');
  Route::post('/register', 'NurseAuth\RegisterController@register');

  Route::post('/password/email', 'NurseAuth\ForgotPasswordController@sendResetLinkEmail')->name('password.request');
  Route::post('/password/reset', 'NurseAuth\ResetPasswordController@reset')->name('password.email');
  Route::get('/password/reset', 'NurseAuth\ForgotPasswordController@showLinkRequestForm')->name('password.reset');
  Route::get('/password/reset/{token}', 'NurseAuth\ResetPasswordController@showResetForm');
});

Route::group(['prefix' => 'doctor'], function () {
  Route::get('/login', 'DoctorAuth\LoginController@showLoginForm')->name('login');
  Route::post('/login', 'DoctorAuth\LoginController@login');
  Route::post('/logout', 'DoctorAuth\LoginController@logout')->name('logout');

  Route::get('/register', 'DoctorAuth\RegisterController@showRegistrationForm')->name('register');
  Route::post('/register', 'DoctorAuth\RegisterController@register');

  Route::get('/addAvailability', 'AvailabilityController@showAddAvailabilityPage');
  Route::post('/api/addAvailability', 'AvailabilityController@store');

  Route::post('/password/email', 'DoctorAuth\ForgotPasswordController@sendResetLinkEmail')->name('password.request');
  Route::post('/password/reset', 'DoctorAuth\ResetPasswordController@reset')->name('password.email');
  Route::get('/password/reset', 'DoctorAuth\ForgotPasswordController@showLinkRequestForm')->name('password.reset');
  Route::get('/password/reset/{token}', 'DoctorAuth\ResetPasswordController@showResetForm');
});
