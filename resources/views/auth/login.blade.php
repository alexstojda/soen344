@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">

@switch(app('request')->input('user'))

    @case("patient")
    <login-component :title="'{{ __('auth.login') }}'" :id="'patient'"></login-component>
    @break

    @case("doctor")
    <login-component :title="'{{ __('auth.login_doctor') }}'" :id="'doctor'"></login-component>
    @break

    @case("nurse")
    <login-component :title="'{{ __('auth.login_nurse') }}'" :id="'nurse'"></login-component>
    @break

    @default
    <login-component :title="'{{ __('auth.login') }}'" :id="'patient'"></login-component>
@endswitch

       
    </div>
</div>
@endsection
