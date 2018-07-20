@component('mail::message')
 Hello {{ $user->first_name}} {{ $user->last_name}},<br/>
 <p>Thank you for registering an account with Canonizer.com.</p>
     
<p>Here is a link to a help index page: @component('mail::button', ['url' => url('/') . '/' . $link]) Click Here
@endcomponent

</p>
<p>If you ever have any issues or feedback, feel free to e-mail: {{ config('app.support_email') }}.</p>

Sincerely,<br>
{{ config('app.email_signature') }}
@endcomponent
	

