@extends('admin.app')
@section('content')
<div class="row">
    <div class="col-md-12 panel-warning">
        @if(session()->has('success'))
            <div class="alert alert-success alert-dismissible"> 
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                {!! session('success') !!}
            </div>
        @endif
        
        
        <div class="content-box-header panel-heading">
            <div class="panel-title ">Templates</div>
            <div class="panel-options">
                    <a href="{{ url('/admin/template/create') }}" data-rel="collapse"><i class="fa fa-plus"></i> Create Template</a>
            </div>

        </div>
        <div class="content-box-large box-with-header">
            <table class="table table-row">
                <tr>
                    <th>Template Name</th>
                    <th>Template Subject</th>
                    <th>Template body</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                @if(isset($templates) && count($templates) > 0)
                @foreach($templates as $template)
                <tr>
                    <td>{{ $template->name }}</td>
                    <td>{{ $template->subject }}</td>
                    <td><div class='temp-body'>{!! $template->body !!}</div></td>
                    <td>{{ ($template->status == 1) ? 'Active' : 'Inactive' }}</td>
                    <td>
                        <a href="{{ url('/admin/template/edit/'.$template->id) }}"><i class="fa fa-edit"></i>&nbsp;&nbsp;Edit</a>
                        &nbsp;&nbsp;<a href="{{ url('/admin/template/delete/'.$template->id) }}"><i class="fa fa-trash"></i>&nbsp;&nbsp;Delete</a>
                    </td>
                </tr>
                @endforeach
                @else
                <tr><td colspan="5"><span>No templates found!</span></td></tr>                
                @endif
            </table>
            {{ $templates->links() }}

        </div>
    </div>
</div>
@endsection