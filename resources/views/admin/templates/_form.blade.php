@if(isset($model))
{{ Form::model($model, ['route' => ['template.update', $model->id], 'method' => 'patch']) }}
@else
{{ Form::open(['route' => 'template.store']) }}
@endif
<input type="hidden" name="_token" value="{{ csrf_token() }}"/>
<fieldset>
    <div class="form-group">
        <label>Template Name</label>
        {{ Form::text('name', old('name'),['class'=>'form-control']) }}
    </div>
    <div class="form-group">
        <label>Template Subject</label>
        {{ Form::text('subject', old('subject'),['class'=>'form-control']) }}
    </div>

    <div class="form-group">
        <label>Template Body</label>
        {{ Form::textarea('body', old('body'),['class'=>'form-control','id'=>'body']) }}
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