@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <h2 class="card-title text-center">Add a new availability</h2>
            <add-availability :doctor-id={{ Auth('doctor')->id() }}></add-availability>
        </div>
    </div>
    <br>
    <div class="card">
        <div class="card-body">
            <h2 class="card-title text-center">Your upcoming availabilities</h2>
            <list-availabilities :show-delete="true" :doctor-id={{ Auth('doctor')->id() }}></list-availabilities>
        </div>
    </div>
</div>
@endsection
