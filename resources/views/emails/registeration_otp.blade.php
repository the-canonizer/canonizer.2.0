@component('mail::message')
 Hello {{ $user->first_name}} {{ $user->last_name}},<br/>
 <p>Thank you for registering an account with Canonizer.com.</p>

 <p>You one time Verification Code: {{$user->otp}}</p>
<p>If you ever have any issues or feedback, feel free to e-mail: {{ config('app.support_email') }}.</p>

Sincerely,<br>
{{ config('app.email_signature') }}
@endcomponent
	

