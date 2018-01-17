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
    @section('sidebar')
        <div id="app">
            
            <nav class="navbar navbar-default">

                <div class="container-fluid">

                    <div class="navbar-header">

                        {{--  <a class="navbar-brand" href="{{ url('/') }}">
                            {{--  <img src="{{ url('/img/logo.png')}}"
                            height="100" width="100"
                            > 
                            Canonizer.com
                        </a>  --}}
                        <!-- Starts Here -->

                        <ul class="nav navbar-nav navbar-left">
                            
                            <li class="dropdown">
                                
                                <a  href="{{ url('/') }}"
                                    class="navbar-brand"
                                    class="dropdown-toggle" 
                                    data-toggle="dropdown" 
                                    role="button" 
                                    aria-haspopup="true" 
                                    aria-expanded="false">Canonizer.com  
                                    <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu">
                                    
                                    <li>
                                        <a href="#">Help</a>
                                    </li>
                                    
                                    <li>
                                        <a href=" {{ url('/') }} ">Canonizer Main</a>
                                    </li>

                                    <li>
                                        <a href="#">What is the Canonizer</a>
                                    </li>
                                    
                                    <li>
                                        <a href="#">Browse</a>
                                    </li>

                                    <li>
                                        <a href="{{ url('/topic/create') }}">Create New Topic</a>
                                    </li>

                                    <li>
                                        <a href="#">Upload File</a>
                                    </li>

                                </ul>
                
                            </li>
                                
                        </ul>
                      
                        <!-- Ends Here -->

                    </div>   
                    
                    <div class="collapse navbar-collapse" id="navDetails">

                        <ul class="nav navbar-nav">
                            
                            <li class="active">
                                <a href="{{ request()->segment(count(request()->segments())) }}/../">Home 
                                    <span class="sr-only">(current)</span>
                                </a>
                            </li>
                        
                            @if(Auth::check())
                        
                            <li>
                                <a href="{{ basename(request()->path()) }}/../create">Create New Thread</a>
                            </li>
                        </ul>
                        
                        <form class="navbar-form navbar-left" role="search">
                            <div class="input-group search-panel">
                                <div class="input-group-btn">
                                    <button type="button" 
                                            class="btn btn-default dropdown-toggle btn-slct-dpdwn" 
                                            data-toggle="dropdown">
                                        
                                        <span id="search_concept">Canonizer.com</span> 
                                        <span class="caret"></span>

                                    </button>

                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="#web">Web</a></li>
                                        <li><a href="#canonizer.com">Canonizer.com</a></li>
                                    </ul>

                                </div>

                                <input type="hidden" name="search_param" value="all" id="search_param">         
                                <input type="text" class="form-control search" name="x" placeholder="Search">
                                
                                <span class="input-group-btn">

                                    <button class="btn btn-default" type="button">Go For It
                                        <span class="glyphicon"></span>
                                    </button>

                                </span>

                            </div>

                        </form>
                    
                        <ul class="nav navbar-nav navbar-right">
                            
                            <li class="dropdown">
                                <a href="#" 
                                    class="dropdown-toggle" 
                                    data-toggle="dropdown" 
                                    role="button" 
                                    aria-haspopup="true" 
                                    aria-expanded="false">{{ Auth::user()->first_name . ' ' . Auth::user()->last_name}} 
                                    <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu">
                                    
                                    <li>
                                        <a href="{{ route('settings')}}">Account Settings</a>
                                    </li>
                                    
                                    <li>
                                        <a href="{{ url('/logout')}}">Logout</a>
                                    </li>
                                    
                                </ul>
                                @else
                                <li>
                                    <a href="{{ url('/login')}}">Login</a>
                                </li>

                                <li>
                                    <a href="{{ url('/register')}}">Register</a>
                                </li>
                
                            </li>
                            @endif
                        </ul>
                        
                    </div>
                
                </div>

            </nav>

        </div>
        

        @yield('content')



<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
@yield('scripts')
</body>
