@if(isset($model))
{{ Form::model($model, ['route' => ['shares.update', $model->id], 'method' => 'patch']) }}
@else
{{ Form::open(['route' => 'shares.store']) }}
@endif
<input type="hidden" name="_token" value="{{ csrf_token() }}"/>
<fieldset>
    <div class="form-group">
        <label>Nick Name Id<span style="color:red">*</span></label>
        {{ Form::text('nick_name_id', old('nick_name_id'),['class'=>'form-control','id'=>'nick_name_id']) }}
         @if ($errors->has('nick_name_id')) <p class="help-block">{{ $errors->first('nick_name_id') }}</p> @endif
         @if(Session::has('nickNameError'))
        <p class="help-block">{{ Session::get('nickNameError') }}</p>
        @endif
    </div>
    <div class="form-group">
        <label>As Of Date<span style="color:red">*</span></label>
        {{Form::text('as_of_date',null,['class'=>'form-control','id'=>'share_as_of_date'])}}
         @if ($errors->has('as_of_date')) <p class="help-block">{{ $errors->first('as_of_date') }}</p> @endif
    </div>

    <div class="form-group">
        <label>Share Value<span style="color:red">*</span></label>
        {{ Form::number('share_value', old('share_value'),['class'=>'form-control','id'=>'share_value']) }}
         @if ($errors->has('share_value')) <p class="help-block">{{ $errors->first('share_value') }}</p> @endif
    </div>

</fieldset>
<div>
    <button class="btn btn-primary">{{ $submitButton }}</button>
</div>
{{ Form::close() }}

<script>
        function formatDate(userDate){
            var date    = new Date(userDate),
            yr      = date.getFullYear(),
            month   = date.getMonth() < 10 ? '0' + (date.getMonth()+1) : (date.getMonth()+1),
            day     = date.getDate()  < 10 ? '0' + date.getDate()  : date.getDate(),
            newDate = day + '/' + month + '/' + yr;
            return newDate;

        }
     var currentYear = new Date().getFullYear();
     var newDate = new Date();
    var date_string =  formatDate(new Date());
    var yearRange = (currentYear - 2021);

    $("#share_as_of_date").val(date_string);
     $("#share_as_of_date").datepicker({
                defaultDate:newDate,
                changeMonth: true,
                changeYear: true,
                dateFormat: 'dd/mm/yy',
                yearRange: "-"+yearRange+":+0",
                maxDate:  new Date()
            });
</script>
