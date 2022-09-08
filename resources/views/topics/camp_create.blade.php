@extends('layouts.app')
@section('content')

<div class="camp top-head">
    <h3><b>Topic:</b>  {{ $topic->topic_name}}</h3>
    <h3><b>Camp:</b> {!! $parentcamp !!}</h3>  
</div>


<div class="page-titlePnl">
    <h1 class="page-title">Create New Camp</h1>
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
    <div class="col-sm-5 margin-btm-2">
        <form action="{{ route('camp.save')}}" method="post" id="campForm">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" id="topic_num" name="topic_num" value="{{ $topic->topic_num }}">
           <!-- <input type="hidden" id="parent_camp_num" name="parent_camp_num" value="{{ $parentcampnum }}">-->
            
            <div class="form-group">
                <label for="camp_name">Nick Name <span style="color:red">*</span></label>
                <select name="nick_name" id="nick_name" class="form-control">
                    @foreach($nickNames as $nick)                   
                    <option value="{{ $nick->id }}"  @if(old('nick_name') == $nick->id) selected @endif>{{ $nick->nick_name}}</option>
                    @endforeach
					
                </select>
                 @if ($errors->has('nick_name')) <p class="help-block">{{ $errors->first('nick_name') }}</p> @endif
				 <?php if(count($nickNames) == 0) { ?>
                     <p style="color:red" class="help-block">Note:You have not yet added a nick name. A public or private nick name must be added then selected here when contributing.</p>
                    <a href="<?php echo url('settings/nickname');?>">
				 Add New Nick Name </a>
				 <?php } ?>
             </div> 
            <div class="form-group">
                <label for="parent_camp_num">Parent Camp <span style="color:red">*</span></label>
                @php
                    function sortByCampName($a, $b) {
                        return $a['camp_name'] >= $b['camp_name'];
                    }
                    usort($parentcampsData, 'sortByCampName');
                @endphp
                <select  name="parent_camp_num" id="parent_camp_num" class="form-control">
                    @foreach($parentcampsData as $parent)
					
                    <option @if( sizeof(old()) > 0 && old('parent_camp_num') == $parent['camp_num']) selected @elseif (sizeof(old()) == 0 && $camp->camp_num == $parent['camp_num']) selected @endif value="{{ $parent['camp_num'] }}">{{ $parent['camp_name']}}</option>
                  
					@endforeach
					
                </select>
                 @if ($errors->has('parent_camp_num')) <p class="help-block">{{ $errors->first('parent_camp_num') }}</p> @endif
				 
             </div> 
             <div class="form-group">
                <label for="camp_name">Camp Name ( Limit 30 Chars ) <span style="color:red">*</span></label>
                <input type="text" onkeydown="restrictTextField(event,30)" name="camp_name" class="form-control" maxlength="30" id="camp_name" value="{{ old('camp_name')}}">
                 @if ($errors->has('camp_name')) <p class="help-block">{{ $errors->first('camp_name') }}</p> @endif
             </div> 
           
            <div class="form-group">
                <label for="keywords">Keywords </label>
                <input type="text" name="keywords" class="form-control" id="keywords" value="{{ old('keywords') }}">
                @if ($errors->has('keywords')) <p class="help-block">{{ $errors->first('keywords') }}</p> @endif
            </div> 
           
           
            <div class="form-group">
                <label for="">Edit summary (Briefly describe your changes)</label>
                <textarea class="form-control" rows="4" name="note" id="note">{{ old('note')}}</textarea>
                @if ($errors->has('note')) <p class="help-block">{{ $errors->first('note') }}</p> @endif
            </div>   
            <div class="form-group">
			   <p style="color:red">The following fields are rarely used and are for advanced users only.</p>
                <label for="camp_about_url">Camp About URL ( Limit 1024 Chars ) </label>
                <input type="text" name="camp_about_url" onkeydown="restrictTextField(event,1024)" class="form-control" id="camp_about_url" value="{{ old('camp_about_url') }}">
                @if ($errors->has('camp_about_url')) <p class="help-block">{{ $errors->first('camp_about_url') }}</p> @endif
            </div> 

             <div class="form-group">
			   <label for="camp_about_nick_id">Camp About Nick Name </label>
                <select name="camp_about_nick_id" id="camp_about_nick_id" class="form-control">
                    <option value="0">--Select Camp About Nick Name--</option>
					@foreach($allNicknames as $aboutnick)
                    <option @if( sizeof(old()) > 0 && old('camp_about_nick_id') == $aboutnick->id) selected @endif value="{{ $aboutnick->id }}">{{ $aboutnick->nick_name}}</option>
                    @endforeach
					
                </select>            
			</div>  
            <div class="form-group">
                <input type="checkbox" id="is_disabled" name="is_disabled" value="1" data-toggle="tooltip" data-placement="top" title="Disable the ability to add additional sub camps.">
                <label for="is_disabled" id="is_disabled_label"> Disable additional sub camps</label>
            </div> 	
            
            <div class="form-group">
                <input type="checkbox" id="is_one_level" name="is_one_level" value="1" data-toggle="tooltip" data-placement="top" title="Disable the ability to add additional sub camps beyond direct sub camps.">
                <label for="is_one_level" id="is_one_level_label"> Single level camps only </label>
            </div>

            <button type="submit" id="submit" class="btn btn-login">Create Camp</button>
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
    </script>
    <script>
    $(document).ready(function () {
        $("#campForm").submit(function (e) {
              
            //disable the submit button
            $("#submit").attr("disabled", true);
            return true;
        });

        $('#is_disabled').click(function() {
            if ($('#is_disabled').is(':checked') === true) {
                $('#is_one_level').prop('checked', false);
                $('#is_one_level').prop('disabled', true);
            } else {
                $('#is_one_level').prop('disabled', false);
            }
        });

        $('#is_disabled_label').hover(function () {
            $('#is_disabled').tooltip().mouseover()
        }, function () {
            $('#is_disabled').tooltip().mouseleave()
        });
        $('#is_one_level_label').hover(function () {
            $('#is_one_level').tooltip().mouseover()
        }, function () {
            $('#is_one_level').tooltip().mouseleave()
        });

        $('#parent_camp_num').select2();
    });
    </script>


    @endsection

