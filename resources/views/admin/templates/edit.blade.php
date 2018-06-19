@extends('admin.app')
@section('content')
<div class="row">
    <div class="col-md-12 panel-warning">
        <div class="content-box-header panel-heading">
            <div class="panel-title ">Update Template</div>
            <div class="panel-options">
                    <a href="{{ url('/admin/templates') }}" data-rel="collapse"><i class="fa fa-cog"></i> Manage All Templates</a>
            </div>


        </div>
        <div class="content-box-large box-with-header">
            @include('admin.templates._form', ['submitButton' => 'Update','model'=>$template])

            <div class="panel-body">
                
            </div>

        </div>
    </div>
</div>
@endsection