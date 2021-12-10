@extends('layouts.app')
@section('content')

<div class="camp top-head">
    <h3><b>Topic:</b>  {{ $topic->topic_name}}</h3>
    <h3><b>Camp:</b> {!! $parentcamp !!}</h3>  
</div>


<div class="page-titlePnl">
    <h1 class="page-title">
	 <?php if($objection=="objection") { ?> 
	Object to this proposed update
	 <?php } else { ?>
	Camp Update
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
    <div class="col-sm-5 margin-btm-2">
        <form action="{{ route('camp.save')}}" onsubmit="return submitForm(this);" method="post">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" id="topic_num" name="topic_num" value="{{ $topic->topic_num }}">
            
			<input type="hidden" id="camp_num" name="camp_num" value="{{ $camp->camp_num }}">
			<input type="hidden" id="submitter" name="submitter" value="{{ $camp->submitter_nick_id }}">
			<?php if($objection=="objection") 
            { 
            ?>
    		 <input type="hidden" name="objection" id="objection" value="1">
    		 <input type="hidden" name="objection_id" id="objection_id" value="{{ $camp->id }}">
			<?php 
            } 
            ?>
                         
            <?php if($campupdate=="update") 
            { 
            ?>
                <input type="hidden" id="camp_update" name="camp_update" value="1">
                <input type="hidden" id="camp_id" name="camp_id" value="{{ $camp->id }}">
            <?php 
            } 
            ?>
           
            <?php if($camp->camp_name=="Agreement") 
            {
            ?>
			 <input type="hidden" id="parent_camp_num" name="parent_camp_num" value="{{ $parentcampnum }}">
			<?php 
            } 
            else 
            { 
            ?>
			 <div class="form-group">
                <label for="parent_camp_num">Parent Camp <span style="color:red">*</span></label>
                <select  name="parent_camp_num" id="parent_camp_num" class="form-control" <?php if($objection=="objection") { ?> disabled <?php } ?>>
                    @foreach($parentcampsData as $parent)
					<?php if(($camp->camp_num != $parent->camp_num ) && ($parent->parent_camp_num<=$parentcampnum)) { 
                    //#787 #861 both
                    //($parent->parent_camp_num<=$parentcampnum) /*siblings and parent both show */
                    //($parent->camp_num<=$parentcampnum) /*only parent show */
                    ?>
                    <option <?php if($camp->parent_camp_num==$parent->camp_num) echo "selected=selected";?> value="{{ $parent->camp_num }}">{{ $parent->camp_name}}</option>
                    <?php } ?>
					@endforeach
					
                </select>
                 @if ($errors->has('parent_camp_num')) <p class="help-block">{{ $errors->first('parent_camp_num') }}</p> @endif
				 
             </div> 
			<?php } ?>
			<div class="form-group">
                <label for="camp_name">Nick Name <span style="color:red">*</span></label>
                <select name="nick_name" id="nick_name" class="form-control">
                    @foreach($nickNames as $nick)
                    <option <?php if($camp->submitter_nick_id==$nick->id) echo "selected=selected";?> value="{{ $nick->id }}">{{ $nick->nick_name}}</option>
                    @endforeach
					
                </select>
                 @if ($errors->has('nick_name')) <p class="help-block">{{ $errors->first('nick_name') }}</p> @endif
				<?php if(count($nickNames) == 0) { ?> 
                     <p style="color:red" class="help-block">Note:You have not yet added a nick name. A public or private nick name must be added then selected here when contributing.</p>
				<a href="<?php echo url('settings/nickname');?>">Add New Nick Name</a>
				<?php } ?>
             </div> 
            
             <div class="form-group">
                <label for="camp_name">Camp Name ( Limit 30 Chars ) <span style="color:red">*</span></label>
                <input type="text" maxlength="30" onkeydown="restrictTextField(event,30)" name="camp_name" <?php if($camp->camp_name=="Agreement") echo "readonly";?> class="form-control" id="camp_name" value="{{ $camp->camp_name}}" <?php if($objection=="objection") { ?> readonly="true" <?php } ?>>
                 @if ($errors->has('camp_name')) <p class="help-block">{{ $errors->first('camp_name') }}</p> @endif
             </div> 
             		
            <?php if($objection=="objection") { ?>
            <div class="form-group">
                <label for="topic name">Your Objection Reason ( Limit 100 Chars ) <span style="color:red">*</span></label>
                <input type="text" name="objection_reason" onkeydown="restrictTextField(event,100)" class="form-control" id="objection_reason" value="">
				@if ($errors->has('objection_reason')) <p class="help-block">{{ $errors->first('objection_reason') }}</p> @endif
            </div> 				
            <?php } else { ?>
			<div class="form-group">
                <label for="keywords">Keywords </label>
                <input type="text" name="keywords" class="form-control" id="keywords" value="{{ $camp->key_words }}">
                @if ($errors->has('keywords')) <p class="help-block">{{ $errors->first('keywords') }}</p> @endif
            </div> 
           
            
            <div class="form-group">
                <label for="">Edit summary (Briefly describe your changes) </label>
             
                <textarea class="form-control" rows="4" name="note" id="note">{{$campupdate == 'update'? $camp->note : ""}}</textarea>

                @if ($errors->has('note')) <p class="help-block">{{ $errors->first('note') }}</p> @endif
            </div>   
            <div class="form-group">
			     <p style="color:red">The following fields are rarely used and are for advanced users only.</p>
                <label for="camp_about_url">Camp About URL ( Limit 1024 Chars ) </label>
                <input type="text" name="camp_about_url" class="form-control" id="camp_about_url" value="{{ $camp->camp_about_url }}">
                @if ($errors->has('camp_about_url')) <p class="help-block">{{ $errors->first('camp_about_url') }}</p> @endif
            </div>
            <div class="form-group" id="camp_about_nickname">
                <label for="camp_about_nick_id">Camp About Nick Name </label>
                <select name="camp_about_nick_id" id="camp_about_nick_id" class="form-control">
                    <option value="0">--Select Camp About Nick Name--</option>
					@foreach($allNicknames as $aboutnick)
                    <option <?php if($camp->camp_about_nick_id==$aboutnick->id) echo "selected=selected";?> value="{{ $aboutnick->id }}">{{ $aboutnick->nick_name}}</option>
                    @endforeach
					
                </select>    
			</div> 			
            <?php } ?>
            <button type="submit" id="submit" class="btn btn-login">
			<?php if($objection=="objection") { ?> Submit Objection <?php } else {?>
			Submit Update <?php } ?>
			</button> 
            
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
        
        function validateURL(link)
        {
            if(link!=""){
                if (link.indexOf("http://") == 0 || link.indexOf("https://") == 0) {
                    return link;
                }
                else{
                    return "http://"+link;
                }
            }
            return "";
        }
    </script>


    @endsection

