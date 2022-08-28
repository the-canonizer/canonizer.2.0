@extends('layouts.app')
@section('content')

<?php if (!empty($topic)) {
                $currentLive = 1;
                $currentTime = time();
                $topicNum = 0;
                $liveTopic = getAgreementTopic($topic->topic_num);
                $topicNum = $topic->topic_num;
                $urltitle      = $topicNum."-".preg_replace('/[^A-Za-z0-9\-]/', '-', $topic->topic_name);
                $url_portion = \App\Model\Camp::getSeoBasedUrlPortion($topicNum,$currentLive);
           ?>
<div class="camp top-head">
    <h3><b>Topic:</b>  <a href="/topic/{{$url_portion}}" >{{ $liveTopic->topic_name ?? '' }}</a></h3>
</div>
<?php } ?>
<div class="page-titlePnl">
    <h1 class="page-title">
	 <?php if($objection=="objection") { ?> 
	Object to this proposed update
	 <?php } else { ?>
	Topic Update
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
        <form action="{{ url('/topic')}}" onsubmit="return submitTopicForm()"  method="post" id="topicForm">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
			<input type="hidden" id="topic_num" name="topic_num" value="{{ $topic->topic_num }}">
			<input type="hidden" id="id" name="id" value="{{ $topic->id }}">
			<input type="hidden" id="submitter" name="submitter" value="{{ $topic->submitter_nick_id }}">
			<?php if($objection=="objection") { ?>
			 <input type="hidden" id="objection" name="objection" value="1">
			 <input type="hidden" id="objection_id" name="objection_id" value="{{ $topic->id}}">
			<?php } ?>
                         
                         <?php if(isset($topicupdate) && $topicupdate=="update") { ?>
			 <input type="hidden" id="topic_update" name="topic_update" value="1">
			 <input type="hidden" id="topic_id" name="topic_id" value="{{ $topic->id}}">
			<?php } ?>
			
			<div class="form-group">
                <label for="camp_name">Nick Name <span style="color:red">*</span></label>
                <select name="nick_name" id="nick_name" class="form-control">
                    @foreach($nickNames as $nick)
                    <option value="{{ $nick->id }}" @if(old('nick_name') == $nick->id) selected @endif>{{ $nick->nick_name }}</option>
                    @endforeach
					
                </select>
                 @if ($errors->has('nick_name')) <p class="help-block">{{ $errors->first('nick_name') }}</p> @endif
				<?php if(count($nickNames) == 0) { ?> 
           <p style="color:red" class="help-block">Note:You have not yet added a nick name. A public or private nick name must be added then selected here when contributing.</p>
          <a href="<?php echo url('settings/nickname');?>">Add New Nick Name </a><?php } ?>
             </div> 
			<div class="form-group">
                <label for="topic name">Topic Name ( Limit 30 Chars ) <span style="color:red">*</span></label>
                <input type="text" name="topic_name" onkeydown="restrictTextField(event,30)" class="form-control" id="topic_name" value="@if(sizeof(old()) > 0) {{ old('topic_name') }} @else {{ $topic->topic_name}} @endif" <?php if($objection=="objection") { ?> readonly="true" <?php } ?>>
				@if ($errors->has('topic_name')) <p class="help-block">{{ $errors->first('topic_name') }}</p> @endif
            </div> 
			<?php if($objection=="objection") { ?>			
            <div class="form-group">
                <label for="topic name">Your Objection Reason ( Limit 100 Chars ) <span style="color:red">*</span></label>
                <input type="text" name="objection_reason" onkeydown="restrictTextField(event,100)" class="form-control" id="objection_reason" value="">
				@if ($errors->has('objection_reason')) <p class="help-block">{{ $errors->first('objection_reason') }}</p> @endif
            </div> 			
            <?php } else { ?>
                       
            <div  class="form-group">
                <label for="namespace">Namespace <span style="color:red">*</span> (General is recommended, unless you know otherwise)</label>
                <select  onchange="selectNamespace(this)" name="namespace" id="namespace" class="form-control">
                   
                    @foreach($namespaces as $namespace)
                    <option value="{{ $namespace->id }}" @if(sizeof(old()) > 0 && old('namespace') == $namespace->id) selected @elseif(sizeof(old()) == 0 && $topic->namespace_id == $namespace->id) selected @endif>{{namespace_label($namespace)}}</option>
                    @endforeach
                    <!-- <option value="other" @if(old('namespace') == 'other') selected @endif>Other</option> -->
                </select>
                <!--
                <input type="text" name="namespace" class="form-control" id="" value="">-->
                @if ($errors->has('namespace')) <p class="help-block namespace-error">{{ $errors->first('namespace') }}</p> @endif
			</div>
            <div id="other-namespace" class="form-group" >
                <label for="namespace">Other Namespace Name ( Limit 100 Chars ) <span style="color:red">*</span></label>
                
                <input type="text" name="create_namespace" onkeydown="restrictTextField(event,100)" class="form-control" id="create_namespace" value="{{old('create_namespace')}}">
                <span class="note-label"><strong>Note</strong>: Namespace for hierarchical categorization of topics. It can be something like: /crypto_currency/, /organizations// etc... It must start and end with "/"</span>
                @if ($errors->has('create_namespace')) <p class="help-block">{{ $errors->first('create_namespace') }}</p> @endif
	        <p class="help-block namespace-error" id="err-other-namespace"></p>
            </div>
         
           
            <div class="form-group">
                <label for="">Edit summary (Briefly describe your changes)</label>
                <textarea class="form-control" rows="4" name="note" id="note">@if(sizeof(old() > 0)) {{ old('note') }} @else {{$topicupdate == 'update'? $topic->note : ""}} @endif</textarea>
				@if ($errors->has('note')) <p class="help-block">{{ $errors->first('note') }}</p> @endif
            </div>

            <div class="form-group">
                <input type="checkbox" id="is_disabled" name="is_disabled" value="1">
                <label for="is_disabled"> Disable additional sub camps</label><br>
                <input type="checkbox" id="is_one_level" name="is_one_level" value="1">
                <label for="is_one_level"> Single level camps only </label><br>
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
                $('#err-other-namespace').text("");
            }else{
              //  $('#namespace').val('');
                $('#other-namespace').css('display','none');
                $('#err-other-namespace').text("");
            }
        }
        selectNamespace();

        function submitTopicForm(e){
           $('button[type="submit"]').attr('disabled','disabled');
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
           if(!valid){
               e.preventDefault();
                $('button[type="submit"]').removeAttr('disabled');
               $('.help-block').text('');
               $('#err-other-namespace').text(message);
               //alert("Error: " + message);
           }
           
            return valid;
        }
        
        // $('#submit').click(function(e) {
        //    // e.preventDefault();
           
            
        // })
        
        
        
        
    </script>


    @endsection

