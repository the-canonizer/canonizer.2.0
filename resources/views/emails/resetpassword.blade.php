@component('mail::message')
 Hello {{ $user->first_name}} {{ $user->last_name }},<br/>
 <p>You recently requested to reset your password. In order to complete your request, click the button below:</p>
     

@component('mail::button', ['url' => url('/') . '/' . $link])
Click Here To Reset Password
@endcomponent


<p>Please note that the link provided to reset the password will expire after 48 hours.</p>

Sincerely,<br>
{{ config('app.email_signature') }}
@endcomponent
