@extends('layouts.forum')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                
                <div class="panel panel-default">
                
                    <div class="panel-heading">Create a new thread for Camp Forum</div>
                   
                    
                    <div class="panel-body">
    
                        <form method="POST" action="/forum/{{ $topicname }}/{{ $campnum }}/threads">
                            {{ csrf_field() }}
                            
                            <div class="form-group">

                                <label for="title">Title of Thread:</label>
                                
                                <input type="text" class="form-control" id="title" placeholder="Title" name="title">
                            
                            </div>

                            <div class="form-group">

                                <label for="body">Content</label>

                                <textarea name="body" id="body" class="form-control" rows="5" placeholder="Write Your Content Here"></textarea>
                           
                            </div>

                            <button type="submit" class="btn btn-primary">Submit</button>

                        </form>

                    </div>
                
                </div>
            
            </div>
        
        </div>
    
    </div>
@endsection