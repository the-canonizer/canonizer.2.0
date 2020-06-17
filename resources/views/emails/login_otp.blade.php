@component('mail::message')
 Hello {{ $user->first_name}} {{ $user->last_name}},<br/>
 
 <p>Your one time verification code is: {{$user->otp}}</p>
<p>If you ever have any issues or feedback, feel free to e-mail: {{ config('app.support_email') }}.</p>

Sincerely,<br>
{{ config('app.email_signature') }}
@endcomponent
	
