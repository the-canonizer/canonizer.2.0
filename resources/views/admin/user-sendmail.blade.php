@extends('admin.app')
@section('content')
<div class="row">
    <div class="col-md-12 panel-warning">
        <div class="alert alert-success alert-dismissible"> 
        @if(session()->has('success'))
            <div class="alert alert-success alert-dismissible"> 
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                {!! session('success') !!}
            </div>
        @endif         
        <div class="content-box-header panel-heading">
            <div class="panel-title ">Send Email</div>
        </div>
        <div class="content-box-large box-with-header">

            <div class="panel-body">
                <form method="POST" action="{{ route('sendmail')}}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                    <fieldset>
                        <div class="form-group">
                            <label>Select Email Template <span style="color:red">*</span></label>
                            <select name="template" class="form-control">
                                <option value="">Choose Template</option>
                                @foreach($templates as $template)
                                <option value="{{ $template->id }}">{{ $template->name}}
                                @endforeach
                            </select>
                            @if($errors->has('template'))<p class="lbl-error">{{ $errors->first('template') }}</p>@endif
                        </div>
                        
                        
                        
                        <div class="form-group">
                            <input type="radio" name="send_to" value="canonizer_1.0" checked class="send-to">Canonizer1 1.0
                            <input type="radio" name="send_to" value="filter_users" class="send-to">Select Users
                        </div>
                        <div class="form-group"  id="user-selection-box" style="display: {{ ($errors->has('users')) ? 'block': 'none' }}">
                            <label style="float:left;width:100%">Select Users</label>
                            <select style="float:left;width:100%" name="users[]" class="form-control user-selection" multiple>
                                @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->first_name . ' ' . $user->last_name}}
                                @endforeach
                            </select>
                            @if($errors->has('users'))<p class="lbl-error">{{ $errors->first('users') }}</p>@endif
                        </div>
                    </fieldset>
                    <div>
                        <button class="btn btn-primary"> <i class="fa fa-save"></i>Send</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<script>

    $(document).on('click', '.close', function () {
        $(this).parent('div').fadeOut();
    });
    
    $(document).ready(function(){
        
        $(".user-selection").select2({
                placeholder: "Select users"
            });
        $('.send-to').click(function(){
            var val = $(this).val();
            if(val == 'canonizer_1.0'){
                $('#user-selection-box').hide();
            }else if(val == 'filter_users'){
                $('#user-selection-box').show();
            }
        })
    })
    
</script>
@endsection