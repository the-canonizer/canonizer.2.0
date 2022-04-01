@extends('layouts.app')
@section('content')
<div class="page-titlePnl">
    <h1 class="page-title">Reset Password</h1>
</div>       	
<div class="right-whitePnl reset-link-sent">
    <h3 class="text-danger">
        <i class="fa fa-times-circle" style="font-size: 50px; vertical-align: middle;"></i> Invalid Link
    </h3>
    <div style="margin-left:60px;">
    <p>{{$error}}</p>
</div>
</div>  <!-- /.right-whitePnl-->
@endsection