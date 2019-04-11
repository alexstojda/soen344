@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <h2 class="card-title text-center">Create an appointment</h2>
            <list-availabilities show-create-appointment >
            </list-availabilities>
        </div>
    </div>
</div>
@endsection
