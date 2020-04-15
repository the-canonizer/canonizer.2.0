@extends('admin.app')
@section('content')
    <div class="row">
        <div class="col-md-12 panel-warning">
            <div class="content-box-header panel-heading">
                <div class="panel-title ">Namespace</div>
                
                <div class="panel-options">
                    <a href="{{ url('/admin/namespace/create') }}" data-rel="collapse"><i class="fa fa-plus"></i> Create Namespace</a>
                </div>
            </div>
            <div class="content-box-large box-with-header">
                <table class="table table-row">
                <tr><th>Namespace Name</th><th>Parent Namespace</th><th>Label</th><th>Action</th></tr>
                @foreach($namespaces as $namespace)
                <tr><td>{{ $namespace->name }}</td><td>{{ $namespace->parentNamespace ? $namespace->parentNamespace->name :'' }}</td><td>{{ $namespace->label }}</td><td><a href="{{ url('/admin/namespace/edit/'.$namespace->id) }}">Edit</a></td></tr>
                @endforeach
                </table>
                {{ $namespaces->links() }}

            </div>
        </div>
    </div>
@endsection