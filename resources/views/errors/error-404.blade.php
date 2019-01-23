@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Oops! Data Could Not Be Found</h2>

        <p>Sorry but the data you are looking for does not exist or have been removed.</p>

        Please Go back to the <a class="btn btn-primary" href="{{ $prev_url }}">  Previous Page</a>
    </div>

@endsection
