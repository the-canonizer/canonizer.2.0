@component('mail::message')
 Hello {{ $user->first_name}} {{ $user->last_name }},<br/>
 <p>You recently requested to reset you password, In order to complete your request click the button below</p>
     

@component('mail::button', ['url' => url('/') . '/' . $link])
Click Here To Reset Password
@endcomponent

Sincerely,<br>
{{ config('app.email_signature') }}
@endcomponent
