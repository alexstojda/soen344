@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Login') }}</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            @switch(app('request')->input('type'))
                                @case('doctor')
                                <div class="form-group row">
                                    <label for="permit_id" class="col-md-4 col-form-label text-md-right">{{ __('Doctor Permit ID') }}</label>

                                    <div class="col-md-6">
                                        <input id="permit_id" type="text" class="form-control{{ $errors->has('permit_id') ? ' is-invalid' : '' }}" name="permit_id" value="{{ old('permit_id') }}" required autofocus>

                                        @if ($errors->has('permit_id'))
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('permit_id') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                @break
                                @case('nurse')
                                <div class="form-group row">
                                    <label for="access_id" class="col-md-4 col-form-label text-md-right">{{ __('Nurse Access Code') }}</label>

                                    <div class="col-md-6">
                                        <input id="access_id" type="text" class="form-control{{ $errors->has('access_id') ? ' is-invalid' : '' }}" name="access_id" value="{{ old('access_id') }}" required autofocus>

                                        @if ($errors->has('access_id'))
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('access_id') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                @break
                                @case('patient')
                                @default
                                <div class="form-group row">
                                    <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                                    <div class="col-md-6">
                                        <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>

                                        @if ($errors->has('email'))
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            @endswitch

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6 offset-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                        <label class="form-check-label" for="remember">
                                            {{ __('Remember Me') }}
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Login') }}
                                    </button>

                                    @if (Route::has('password.request'))
                                        <a class="btn btn-link" href="{{ route('password.request') }}">
                                            {{ __('Forgot Your Password?') }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

{{--
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
--}}
