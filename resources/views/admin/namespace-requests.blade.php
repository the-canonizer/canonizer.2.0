@extends('admin.app')
@section('content')
    <div class="row">
        <div class="col-md-12 panel-warning">
            <div class="content-box-header panel-heading">
                <div class="panel-title ">Namespace Requests</div>
                
                <div class="panel-options">
                    <a href="{{ url('/admin/namespace/create') }}" data-rel="collapse"><i class="fa fa-plus"></i> Create Namespace</a>
                </div>
            </div>
            <div class="content-box-large box-with-header">
                <table class="table table-row">
                <tr><th>Namespace Name</th><th>Topic Name</th><th>Status</th><th>Action</th></tr>
                @foreach($namespacesrequest as $namespace)
                <tr><td>{{ $namespace->name }}</td><td>{{ $namespace->topic ? $namespace->topic->topic_name : '' }}</td><td><span class="badge {{ $namespace->status ? 'badge-success' : 'badge-info' }}">{{ $namespace->status ? 'Created' : 'Pending' }}</span></td><td><a href="{{ url('/admin/namespace/create?request_id='.$namespace->id) }}">Create Namespace</a></td></tr>
                @endforeach
                </table>
                {{ $namespacesrequest->links() }}

            </div>
        </div>
    </div>
@endsection