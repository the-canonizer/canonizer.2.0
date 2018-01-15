<!DOCTYPE html>

<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Canonizer') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/0.11.1/trix.css">
    <link href="{{ URL::asset('/css/canonizer.css') }}" rel="stylesheet">

    <!-- Scripts -->
    <script>
        window.App = {!! json_encode([
            'csrfToken' => csrf_token(),
            'user' => Auth::user(),
            'signedIn' => Auth::check()
        ]) !!};
    </script>

    <style>
        body { padding-bottom: 100px; }
        .level { display: flex; align-items: center; }
        .level-item { margin-right: 1em; }
        .flex { flex: 1; }
        .mr-1 { margin-right: 1em; }
        .ml-a { margin-left: auto; }
        [v-cloak] { display: none; }
        .ais-highlight > em { background: yellow; font-style: normal; }
    </style>


    @yield('head')
</head>
<body>
<div id="app">

    <nav class="navbar navbar-expand-lg" id="mainNav">
        <a class="navbar-brand" href="{{ url('/') }}">
            <img src="{{ url('/img/logo.png')}}">
        </a>
        <div class="collapse navbar-collapse" id="navbarResponsive">
                
            <ul>
                <li class="nav-item dropdown_li">
                    
                    @if(Auth::check())
                    <div class="dropdown">
                        <a href="">Main Forum</a>
                        <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-fw fa-user"></i> {{ Auth::user()->first_name . ' ' . Auth::user()->last_name}} </a>
                        <span class="caret"></span>
                        <ul class="dropdown-menu">
                            <li><a href="{{ route('settings')}}">Account Settings</a></li>
                            <li><a href="{{ url('/logout')}}">Logout</a></li>
                        </ul>                        

                    </div>
                    @else
                    <a href="/">Main Forum</a>
                    <a class="nav-link" href="{{ url('/login')}}"><i class="fa fa-fw fa-user"></i> Log in</a>
                    <a class="nav-link" href="{{ url('/register')}}"><i class="fa fa-fw fa-user-plus"></i> Register </a>
                    @endif
                </li>
            </ul>
        </div>
    
    </nav> 

    @yield('content')

</div>

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
@yield('scripts')
</body>
