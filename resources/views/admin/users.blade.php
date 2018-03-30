@extends('admin.app')
@section('content')
    <div class="row">
        <div class="col-md-12 panel-warning">
            <div class="content-box-header panel-heading">
                <div class="panel-title ">Users</div>
                
                
            </div>
            <div class="content-box-large box-with-header">
                <table class="table table-row">
                <tr><th>Name</th><th>Email</th><th>Action</th></tr>
                @foreach($users as $user)
                <tr><td>{{ $user->name }}</td><td>{{ $user->email }}</td><td><a href="{{ url('/admin/users/edit/'.$user->id) }}"><i class="fa fa-edit"></i>&nbsp;&nbsp;Edit</a></td></tr>
                @endforeach
                </table>
                {{ $users->links() }}

            </div>
        </div>
    </div>
@endsection