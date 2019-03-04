@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <br/>

                    <div>
                        <h2>Schedule an Appointment</h2>
                    </div>

                    <hr/>

                    <div>
                        <search-appointment></search-appointment>
                    </div>

                    <br/>
                    <hr/>
                    <br/>

                    <div>
                        <h2>Add Appointment</h2>
                    </div>

                    <hr/>

                    <div>
                        <add-appointment></add-appointment>
                    </div>

                    <br/>
                </div>
            </div>
        </div>
    </div>
@endsection
