@component('mail::message')
 Hello {{ $user->first_name}} {{ $user->last_name}},<br/>
 <p>Thank you for registering an account with canonizer.com.</p>

 <p>Your one time verification code is: {{$user->otp}}</p>
<p>If you ever have any issues or feedback, feel free to e-mail: <a href="mailto:{{ config('app.support_email') }}">{{ config('app.support_email') }}</a>.</p>

Sincerely,<br>
{{ config('app.email_signature') }}
@endcomponent
	

