@extends('admin.app')
@section('content')
<div class="row">
    <div class="col-md-12 panel-warning">
        <div class="content-box-header panel-heading">
            <div class="panel-title ">Create Share</div>
            <div class="panel-options">
                    <a href="{{ url('/admin/shares') }}" data-rel="collapse"><i class="fa fa-cog"></i> Manage All Shares</a>
            </div>


        </div>
        <div class="content-box-large box-with-header">
            @include('admin.shares._form', ['submitButton' => 'Create'])

            <div class="panel-body">
                
            </div>

        </div>
    </div>
</div>
@endsection