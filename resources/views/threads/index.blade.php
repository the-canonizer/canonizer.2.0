@extends('layouts.app')

@section('content')

    <!-- Start From Here -->
        
    <!-- Ends Here -->

    <div class="container">
        <div class="row">
            
            <div class="col-md-8 col-md-offset-2"> 
                <div class="panel panel-group">
                    <table class = "table">
                        <ul class = "list-group">
                            <li class = "list-group-item">
                                <div class="panel-heading"><h3>Canonizer Forum Details: </h3>
                                    <h4> Topic Name  : {{ $topicGeneralName }} </h4>
                                    <h4> Camp Name  : {{ $campname }}</h4>
                                </div>
                            </li>
                        </ul>
                    </table>              
                    
                    <div class="panel panel-title">
                        <h5>List of all the Threads</h5>
                    </div>

                    <div class="panel-body">
                        <table class="table">
                            @foreach ($threads as $thread)
                            <article></article>
                                <h5>
                                    <ul class = "list-group">
                                        <li class = "list-group-item">
                                            <a href="{{ URL::to('/')}}/forum/{{ $topicname }}/{{ $campnum }}/threads/{{ $thread->id }}">
                                            {{ $thread->title }}
                                            </a>
                                        </li>
                                       
                                    </ul>
                                </h5>
                                {{--  <div class="body"> {{ $thread->body }} </div>  --}}
                            </article>
                            @endforeach
                        </table>
                        
                    </div>

                </div>

            </div>
            
        </div>
    </div>
@endsection