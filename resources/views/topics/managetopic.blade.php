@extends('layouts.app')
@section('content')
<div class="page-titlePnl">
    <h1 class="page-title">
	 <?php if($objection=="objection") { ?> 
	Object to this proposed update
	 <?php } else { ?>
	Topic update
	 <?php } ?>
	</h1>
</div> 

@if(Session::has('error'))
<div class="alert alert-danger">
    <strong>Error! </strong>{{ Session::get('error')}}    
</div>
@endif

@if(Session::has('success'))
<div class="alert alert-success">
    <strong>Success! </strong>{{ Session::get('success')}}    
</div>
@endif


<div class="right-whitePnl">
   <div class="row col-sm-12 justify-content-between">
    <div class="col-sm-6 margin-btm-2">
        <form action="{{ url('/topic')}}" method="post" id="topicForm">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
			<input type="hidden" id="topic_num" name="topic_num" value="{{ $topic->topic_num }}">
			<input type="hidden" id="submitter" name="submitter" value="{{ $topic->submitter_nick_id }}">
			<?php if($objection=="objection") { ?>
			 <input type="hidden" id="objection" name="objection" value="1">
			 <input type="hidden" id="objection_id" name="objection_id" value="{{ $topic->id}}">
			<?php } ?>
			
			<div class="form-group">
                <label for="camp_name">Nick Name <span style="color:red">*</span></label>
                <select name="nick_name" id="nick_name" class="form-control">
                    @foreach($nickNames as $nick)
                    <option value="{{ $nick->id }}">{{ $nick->nick_name}}</option>
                    @endforeach
					
                </select>
                 @if ($errors->has('nick_name')) <p class="help-block">{{ $errors->first('nick_name') }}</p> @endif
				<?php if(count($nickNames) == 0) { ?> <a href="<?php echo url('settings/nickname');?>">Add New Nick Name </a><?php } ?>
             </div> 
			<div class="form-group">
                <label for="topic name">Topic Name ( Limit 30 char ) <span style="color:red">*</span></label>
                <input type="text" name="topic_name" class="form-control" id="topic_name" value="{{ $topic->topic_name}}">
				@if ($errors->has('topic_name')) <p class="help-block">{{ $errors->first('topic_name') }}</p> @endif
            </div> 
			<?php if($objection=="objection") { ?>			
            <div class="form-group">
                <label for="topic name">Your Objection Reason <span style="color:red">*</span></label>
                <input type="text" name="objection_reason" class="form-control" id="objection_reason" value="">
				@if ($errors->has('objection_reason')) <p class="help-block">{{ $errors->first('objection_reason') }}</p> @endif
            </div> 			
            <?php } else { ?>
                       
            <div  class="form-group">
                <label for="namespace">Namespace <span style="color:red">*</span> (General is recommended, unless you know otherwise)</label>
                <select  onchange="selectNamespace(this)" name="namespace" id="namespace" class="form-control">
                   
                    @foreach($namespaces as $namespace)
                    <option value="{{ $namespace->id }}" @if($topic->namespace_id == $namespace->id) selected @endif>{{$namespace->label}}</option>
                    @endforeach
                    <option value="other" @if(old('namespace') == 'other') selected @endif>Other</option>
                </select>
                <!--
                <input type="text" name="namespace" class="form-control" id="" value="">-->
                @if ($errors->has('namespace')) <p class="help-block">{{ $errors->first('namespace') }}</p> @endif
			</div>
            <div id="other-namespace" class="form-group" >
                <label for="namespace">Other Namespace Name <span style="color:red">*</span></label>
                
                <input type="text" name="create_namespace" class="form-control" id="create_namespace" value="">
                <span class="note-label"><strong>Note</strong>: Name space for hierarchical categorization of topics. It can be something like: /crypto_currency/, /organizations// etc... It must start and end with "/"</span>
                @if ($errors->has('create_namespace')) <p class="help-block">{{ $errors->first('create_namespace') }}</p> @endif
			</div>
         
           
            <div class="form-group">
                <label for="">Additional Note</label>
                <textarea class="form-control" rows="4" name="note" id="note"> </textarea>
				@if ($errors->has('note')) <p class="help-block">{{ $errors->first('note') }}</p> @endif
            </div>
            <?php } ?>
            <?php if($objection=="objection") { ?>
            <button type="submit" id="submit-objection" class="btn btn-login">Submit Objection</button>
             <?php } else {?>
            <button type="submit" id="submit" class="btn btn-login">Submit Update<?php } ?></button>
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
              //  $('#namespace').val('');
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

