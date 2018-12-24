@extends('layouts.app')

@section('content')
    <div class="container">
        <h1> Error: 404</h1>
        <h2>Oops! This Page Could Not Be Found</h2>

        <p>Sorry but the page you are looking for does not exist, have been removed. name changed or is temporarily unavailable</p>

        Go back to <a class="btn btn-primary" href="{{ $prev_url }}"> Previous Page.</a>
    </div>

@endsection
