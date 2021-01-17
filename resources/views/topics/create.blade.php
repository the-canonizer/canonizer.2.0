@extends('layouts.app')
@section('content')
<div class="page-titlePnl">
    <h1 class="page-title">Create New Topic</h1>
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
        <form action="{{ url('/topic')}}" method="post" id="topicForm">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="form-group">
                <label for="camp_name">Nick Name <span style="color:red">*</span><p class="help-block">(Once you pick a nick name, for any contribution to a topic, you must always use the same nick name for any other contribution or forum post to this topic.)</p></label>
                <select name="nick_name" id="nick_name" class="form-control">
                    @foreach($nickNames as $nick)
                    <option value="{{ $nick->id }}">{{ $nick->nick_name}}</option>
                    @endforeach
					
                </select>
                 @if ($errors->has('nick_name')) <p class="help-block">{{ $errors->first('nick_name') }}</p> @endif
				 <?php if(count($nickNames) == 0) { ?>
           <p style="color:red" class="help-block">Note:You have not yet added a nick name. A public or private nick name must be added then selected here when contributing.</p>
				 <a href="<?php echo url('settings/nickname');?>">Add New Nick Name </a>
				 <?php } ?>
            </div> 
            <div class="form-group">
                <label for="topic name">Topic Name ( Limit 30 Chars ) <span style="color:red">*</span></label>
                <input type="text" onkeydown="restrictTextField(event,30)" name="topic_name" class="form-control" id="topic_name" value="{{ old('topic_name')}}">
				@if ($errors->has('topic_name')) <p class="help-block">{{ $errors->first('topic_name') }}</p> @endif
            </div>            
            <div  class="form-group">
                <label for="namespace">Namespace <span style="color:red">*</span>(General is recommended, unless you know otherwise)</label>
                <select  onchange="selectNamespace(this)" name="namespace" id="namespace" class="form-control">
                    
                    @foreach($namespaces as $namespace)
                    <option value="{{ $namespace->id }}" >{{namespace_label($namespace)}}</option>
                    @endforeach
                    <!-- <option value="other" @if(old('namespace') == 'other') selected @endif>Other</option> -->
                </select>
                <!--
                <input type="text" name="namespace" class="form-control" id="" value="">-->
                @if ($errors->has('namespace')) <p class="help-block">{{ $errors->first('namespace') }}</p> @endif
			</div>
            <div id="other-namespace" class="form-group" >
                <label for="namespace">Other Namespace Name ( Limit 100 Chars ) <span style="color:red">*</span></label>
                
                <input type="text" onkeydown="restrictTextField(event,30)" name="create_namespace" class="form-control" id="create_namespace" value="{{ old('create_namespace')}}">
                <span class="note-label"><strong>Note</strong>: Name space for hierarchical categorization of topics. It can be something like: /crypto_currency/, /organizations/ etc... It must start and end with "/"</span>
                @if ($errors->has('create_namespace')) <p class="help-block">{{ $errors->first('create_namespace') }}</p> @endif
                <p class="help-block" id="err-other-namespace"></p>
            </div>
            <div class="form-group">
                <label for="">Edit summary (Briefly describe your changes)</label>
                <textarea class="form-control" rows="4" name="note" id="note">{{ old('note') }}</textarea>
                @if ($errors->has('note')) <p class="help-block">{{ $errors->first('note') }}</p> @endif
            </div>    

            <button type="submit" id="submit" class="btn btn-login">Create Topic</button>
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
                $('#err-other-namespace').text("");
            }else{
               // $('#namespace').val('');
                $('#other-namespace').css('display','none');
                $('#err-other-namespace').text("");
            }
        }
        selectNamespace();
        
        $('#submit').click(function(e) {
           // e.preventDefault();
           var valid = true;
           var message = "";
           if($('#namespace').val() == 'other'){
              var othernamespace = ($('#create_namespace').val()).trim();
			  othernamespace = othernamespace.toLowerCase();
               if(othernamespace == ''){
                   valid = false;
                   message = "The Other Namespace Name field is required when namespace is other.";
               }
               
               $("#namespace option").each(function()
                {
                    var thistext = $(this).text(); 
					thistext = thistext.toLowerCase();
                    if((thistext == othernamespace) || (thistext == '/'+ othernamespace + '/' ) || (thistext == '/'+ othernamespace) || (thistext == othernamespace + '/' )){
                        valid = false;
                        message = "Namespace already exists";
                    };
                });
           }
           if(valid){
               $('#topicForm').submit();
           }else{
               e.preventDefault();
			    $('.help-block').text('');
                $('#err-other-namespace').text(message);
               return false;
           }
            
        })
    </script>


    @endsection

