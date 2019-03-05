@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">

@switch(app('request')->input('user'))

    @case("patient")
    <login-component :title="'{{ __('auth.login') }}'" :role="'patient'"></login-component>
    @break

    @case("doctor")
    <login-component :title="'{{ __('auth.login_doctor') }}'" :role="'doctor'"></login-component>
    @break

    @case("nurse")
    <login-component :title="'{{ __('auth.login_nurse') }}'" :role="'nurse'"></login-component>
    @break

    @default
    <login-component :title="'{{ __('auth.login') }}'" :role="'patient'"></login-component>
@endswitch

       
    </div>
</div>
@endsection
