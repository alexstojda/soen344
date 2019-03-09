
<nav class="navbar navbar-expand-md navbar-light navbar-laravel">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            {{ config('app.name', 'Bon Matin Docteur') }}
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">

            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <!-- Authentication Links -->
                @guest
                    <li class="nav-item dropdown">
                        <a id="navbarLoginDropdown" class="nav-link dropdown-toggle" href="#"
                           role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            Login As <span class="caret"></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarLoginDropdown">
                            <a class="dropdown-item" href="{{ url('login') . '?type=patient' }}">{{ __('Patient') }}</a>
                            <a class="dropdown-item" href="{{ url('login') . '?type=doctor' }}">{{ __('Doctor') }}</a>
                            <a class="dropdown-item" href="{{ url('login') . '?type=nurse' }}">{{ __('Nurse') }}</a>
                        </div>
                    </li>
                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                    @endif
                @else
                    @if(Auth('doctor')->user())
                    <li class="nav-item">
                        <a class="nav-link" href="/doctor/addAvailability">Add Availability <span class="sr-only">(current)</span></a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="/doctor/viewAppointments">View Appointments <span class="sr-only">(current)</span></a>
                    </li>
                    @endif

                    @if(!Auth('doctor')->user())
                        @if(Auth('nurse')->user())
                            <li class="nav-item">
                                <a class="nav-link" href="/nurse/createAppointment">Schedule an Appointment</a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="/viewAppointments">View Appointments <span class="sr-only">(current)</span></a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="/createAppointment">Schedule an Appointment</a>
                            </li>
                        @endif

                    <li class="nav-item">
                        <cart></cart>
                        {{--:user-type={{ Auth::guard()->getName() }}--}}
                    </li>
                    @endif

                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
