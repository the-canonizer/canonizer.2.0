@if(isset($model))
{{ Form::model($model, ['route' => ['template.update', $model->id], 'method' => 'patch','onsubmit'='submitForm(this)']) }}
@else
{{ Form::open(['route' => 'template.store','onsubmit'='submitForm(this)']) }}
@endif
<input type="hidden" name="_token" value="{{ csrf_token() }}"/>
<fieldset>
    <div class="form-group">
        <label>Template Name ( Limit 100 Chars ) <span style="color:red">*</span></label>
        {{ Form::text('name', old('name'),['class'=>'form-control','onkeydown' => "restrictTextField(event,100)"]) }}
         @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
    </div>
    <div class="form-group">
        <label>Template Subject ( Limit 100 Chars ) <span style="color:red">*</span></label>
        {{ Form::text('subject', old('subject'),['class'=>'form-control','onkeydown' => "restrictTextField(event,100)"]) }}
         @if ($errors->has('subject')) <p class="help-block">{{ $errors->first('subject') }}</p> @endif
    </div>

    <div class="form-group">
        <label>Template Body <span style="color:red">*</span></label>
        {{ Form::textarea('body', old('body'),['class'=>'form-control','id'=>'body']) }}
         @if ($errors->has('body')) <p class="help-block">{{ $errors->first('body') }}</p> @endif
    </div>

    <div class="form-group">
        <label>Status</label>
        {{ Form::select('status', array('1' => 'Active', '0' => 'Inactive'), isset($model) ? $model->status : '1',['class'=>'form-control']) }}
    </div>
</fieldset>
<div>
    <button class="btn btn-primary">{{ $submitButton }}</button>
</div>
{{ Form::close() }}

<script>
    // Replace the <textarea id="editor1"> with a CKEditor
    // instance, using default configuration.
    CKEDITOR.replace('body');
</script>