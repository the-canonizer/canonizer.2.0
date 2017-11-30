@extends('layouts.app')
@section('content')
<div class="page-titlePnl">
    <h1 class="page-title">Confirmation</h1>
</div>       	
<div class="right-whitePnl reset-link-sent">
    <h3>
        <img src="{{ url('/img/tick_sended.png')}}" /> Reset Link Sent!
    </h3>
    <div style="margin-left:60px;">
    <p>Reset Password Link has been sent to your email, check email and click "Reset Password" to change password.</p>
    <small>
        Note: If you do not receive the email in few minutes:
        <ul>
            <li>check spam folder</li>
            <li>verify if you typed your email correctly</li>
            <li>if you can't resolve the issue, please contact support@canonizer.com</li>
        </ul>

</div>
</div>  <!-- /.right-whitePnl-->
@endsection