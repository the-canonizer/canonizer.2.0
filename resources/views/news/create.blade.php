@extends('layouts.app')
@section('content')
<div class="page-titlePnl">
    <h1 class="page-title">Add News</h1>
</div> 

@if(Session::has('error'))
<div class="alert alert-danger">
    <strong>Error!</strong>{{ Session::get('error')}}    
</div>
@endif

@if(Session::has('success'))
<div class="alert alert-success">
    <strong>Success!</strong>{{ Session::get('success')}}    
</div>
@endif


<div class="right-whitePnl">
   <div class="row col-sm-12 justify-content-between">
    <div class="col-sm-6 margin-btm-2">
        <form action="{{ url('/newsfeed/save')}}" method="post" id="topicForm">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="topic_num" value="{{$topicnum}}"/>
            <input type="hidden" name="camp_num" value="{{$campnum}}" />
            <input type="hidden" name="topic_slug" value="{{$topic}}" />
           
            <div class="form-group">
                <label for="topic name">Display Text ( Limit 256 Characters ) <span style="color:red">*</span></label>
                <textarea type="text" name="display_text" class="form-control" id="display_text">{{ old('display_text')}}</textarea>
				@if ($errors->has('display_text')) <p class="help-block">{{ $errors->first('display_text') }}</p> @endif
            </div>            
            <div  class="form-group">
                <label for="namespace">Link ( Limit 2000 Characters )<span style="color:red">*</span></label>
                <input type="text" maxlength="2000" name="link" class="form-control" id="link" value="{{old('link')}}">
                @if ($errors->has('link')) <p class="help-block">{{ $errors->first('link') }}</p> @endif
	    </div>
            
            <div  class="form-group">
                <input type="checkbox" name="available_for_child" value="1">Available for child camps
            </div>
            
            <button type="submit" id="submit" class="btn btn-login">Create News</button>
            <a href="{{ url('topic/' . $topic . '/' . $campnum)}}" class="btn btn-default">Cancel</a>
        </form>
    </div>
 </div>   
</div>  <!-- /.right-whitePnl-->

    <script>
        $(document).ready(function () {
            $("#datepicker").datepicker({
                changeMonth: true,
                changeYear: true
            });
            
        })
        function selectNamespace(){
            if($('#namespace').val() == 'other'){
                $('#other-namespace').css('display','block');
            }else{
               // $('#namespace').val('');
                $('#other-namespace').css('display','none');
            }
        }
        selectNamespace();
        
        $('#submit').click(function(e) {
           // e.preventDefault();
           var valid = true;
           var message = "";
           if($('#namespace').val() == 'other'){
               var othernamespace = $('#create_namespace').val();
               if(othernamespace == ''){
                   valid = false;
                   message = "The Other Namespace Name field is required when namespace is other.";
               }
               
               $("#namespace option").each(function()
                {
                    if(($(this).text() == othernamespace) || ($(this).text() == '/'+ othernamespace + '/' ) ){
                        valid = false;
                        message = "Namespace already exists";
                    };
                });
           }
           if(valid){
               $('#topicForm').submit();
           }else{
               e.preventDefault();
               alert("Error: " + message);
               return false;
           }
            
        })
    </script>


    @endsection

