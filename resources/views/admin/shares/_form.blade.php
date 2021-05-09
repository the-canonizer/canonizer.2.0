@if(isset($model))
{{ Form::model($model, ['route' => ['shares.update', $model->id], 'method' => 'patch']) }}
@else
{{ Form::open(['route' => 'shares.store']) }}
@endif
<input type="hidden" name="_token" value="{{ csrf_token() }}"/>
<fieldset>
    <div class="form-group">
        <label>Nick Name <span style="color:red">*</span></label>
        <!-- {{ Form::select('nick_name_id', $nick_names,null,['class'=>'form-control'])}} -->
        {{ Form::text('nick_name_id', old('nick_name_id'),['class'=>'form-control','id'=>'nick_name_id']) }}
         @if ($errors->has('nick_name_id')) <p class="help-block">{{ $errors->first('nick_name_id') }}</p> @endif
         @if(Session::has('nickNameError'))
        <p class="help-block">{{ Session::get('nickNameError') }}</p>
        @endif
    </div>
    <div class="form-group">
        <label>As Of Date<span style="color:red">*</span></label>
        {{Form::selectMonth('as_of_date',null,['class'=>'form-control'])}}
         @if ($errors->has('as_of_date')) <p class="help-block">{{ $errors->first('as_of_date') }}</p> @endif
    </div>

    <div class="form-group">
        <label>Share Value<span style="color:red">*</span></label>
        {{ Form::text('share_value', old('share_value'),['class'=>'form-control','id'=>'share_value']) }}
         @if ($errors->has('share_value')) <p class="help-block">{{ $errors->first('share_value') }}</p> @endif
    </div>

</fieldset>
<div>
    <button class="btn btn-primary">{{ $submitButton }}</button>
</div>
{{ Form::close() }}
