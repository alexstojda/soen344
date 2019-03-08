@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <br>
                    <div class="container">
                        <div class="card">
                            <div class="card-body">
                                <h2 class="card-title text-center">Schedule an Appointment</h2>
                                <search-appointment></search-appointment>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="container">
                        <div class="card">
                            <div class="card-body">
                                <h2 class="card-title text-center">Add Appointment</h2><br>
                                <add-appointment></add-appointment>
                            </div>
                        </div>
                    </div>
                    <br/>
                </div>
            </div>
        </div>
    </div>
@endsection
